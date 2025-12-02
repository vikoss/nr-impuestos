<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException; // añadido

class PdfSigner
{
    public function annotateLastPage(string $pdfContent, string $qrPngContent, string $text, array $options = []): string
    {
        // QR options
        $qrSizePt = $options['qr_size_pt'] ?? 80;
        $qrXOverride = $options['qr_x_pt'] ?? null; // absolute X
        $qrYOverride = $options['qr_y_pt'] ?? null; // absolute Y

        // Text options (absolute positioning + width)
        $textX = $options['text_x_pt'] ?? null; // absolute X
        $textY = $options['text_y_pt'] ?? null; // absolute Y (top of first line)
        $textMaxWidth = $options['text_max_width_pt'] ?? null; // wrap width in points

        // Fallback layout options (used only if absolute coords not provided)
        $marginPt = $options['margin_pt'] ?? 24;
        $placement = $options['placement'] ?? 'bottom_right';
        $gapPt    = $options['gap_pt'] ?? 8;

        // Font options
        $font     = $options['font'] ?? 'Helvetica';
        $fontSize = $options['font_size'] ?? 9;
        $lineHeight = $options['line_height'] ?? 12;

        $src = tempnam(sys_get_temp_dir(), 'pdfsrc_');
        file_put_contents($src, $pdfContent);

        // NUEVO: normalizar PDF si qpdf disponible
        $normalized = $this->normalizePdf($src);

        $qr = tempnam(sys_get_temp_dir(), 'qr_');
        file_put_contents($qr, $qrPngContent);

        $pdf = new Fpdi('P', 'pt');

        try {
            $pageCount = $pdf->setSourceFile($normalized);
        } catch (CrossReferenceException $e) {
            throw new \RuntimeException(
                'PDF con compresión no soportada por FPDI free. Instala qpdf (brew install qpdf) o usa parser comercial.',
                0,
                $e
            );
        }

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $tplId = $pdf->importPage($pageNo);
            $size  = $pdf->getTemplateSize($tplId);
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tplId, 0, 0, $size['width'], $size['height']);

            if ($pageNo === $pageCount) {
                $pageWidth  = $size['width'];
                $pageHeight = $size['height'];

                $pdf->SetFont($font, '', $fontSize);

                // Determine text wrapping width
                $computedTextWidth = is_numeric($textMaxWidth)
                    ? (float) $textMaxWidth
                    : ($pageWidth - (2 * $marginPt));

                $lines      = $this->wrapTextLines($pdf, $text, $computedTextWidth);
                $textHeight = max(count($lines), 1) * $lineHeight;

                // QR absolute position if provided; otherwise simple fallback
                if (is_numeric($qrXOverride) && is_numeric($qrYOverride)) {
                    $qrX = (float) $qrXOverride;
                    $qrY = (float) $qrYOverride;
                } else if ($placement === 'middle_left') {
                    $qrX = $marginPt;
                    $qrY = ($pageHeight / 2) - ($qrSizePt / 2);
                } else {
                    // bottom-right by default
                    $qrX = $pageWidth - $marginPt - $qrSizePt;
                    $qrY = $pageHeight - $marginPt - $qrSizePt - $textHeight - $gapPt;
                }

                $pdf->Image($qr, $qrX, $qrY, $qrSizePt, $qrSizePt, 'PNG');

                // Text absolute position if provided; otherwise place below QR and align to left margin
                $textStartX = $marginPt;
                $textStartY = $qrY + $qrSizePt + $gapPt;
                if (!is_null($textX) && !is_null($textY)) {
                    $textStartX = (float) $textX;
                    $textStartY = (float) $textY;
                }
                $pdf->SetXY($textStartX, $textStartY);
                $pdf->SetTextColor(0, 0, 0);
                // Keep X fixed for each wrapped line so alignment stays consistent
                foreach ($lines as $line) {
                    $pdf->SetX($textStartX);
                    $pdf->Cell($computedTextWidth, $lineHeight, $line, 0, 1, 'L');
                }
            }
        }

        $result = $pdf->Output('S');

        @unlink($src);
        if ($normalized !== $src) {
            @unlink($normalized); // borrar archivo normalizado si se creó
        }
        @unlink($qr);

        return $result;
    }

    // NUEVO: método mínimo para normalizar
    private function normalizePdf(string $inputPath): string
    {
        $qpdfPath = trim((string) @shell_exec('command -v qpdf'));
        if ($qpdfPath === '') {
            return $inputPath;
        }

        $normalized = $inputPath . '.normalized.pdf';
        $cmd = [$qpdfPath, '--object-streams=disable', $inputPath, $normalized];
        $escaped = implode(' ', array_map('escapeshellarg', $cmd));
        exec($escaped, $out, $code);

        if ($code !== 0 || !is_file($normalized) || filesize($normalized) === 0) {
            return $inputPath;
        }

        return $normalized;
    }

    private function wrapTextLines(Fpdi $pdf, string $text, float $maxWidth): array
    {
        // igual que antes
        $words = preg_split('/\s+/', trim($text));
        $lines = [];
        $current = '';

        foreach ($words as $word) {
            $test = $current === '' ? $word : $current . ' ' . $word;
            if ($pdf->GetStringWidth($test) <= $maxWidth) {
                $current = $test;
            } else {
                if ($current !== '') {
                    $lines[] = $current;
                }
                if ($pdf->GetStringWidth($word) > $maxWidth) {
                    $lines = array_merge($lines, $this->hardSplitWord($pdf, $word, $maxWidth));
                    $current = '';
                } else {
                    $current = $word;
                }
            }
        }
        if ($current !== '') {
            $lines[] = $current;
        }

        return $lines ?: [''];
    }

    private function hardSplitWord(Fpdi $pdf, string $word, float $maxWidth): array
    {
        // igual que antes
        $chunks = [];
        $buffer = '';
        $len = mb_strlen($word);
        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($word, $i, 1);
            $test = $buffer . $char;
            if ($pdf->GetStringWidth($test) <= $maxWidth) {
                $buffer = $test;
            } else {
                if ($buffer !== '') {
                    $chunks[] = $buffer;
                }
                $buffer = $char;
            }
        }
        if ($buffer !== '') {
            $chunks[] = $buffer;
        }
        return $chunks;
    }
}
