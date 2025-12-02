<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException; // añadido

class PdfSigner
{
    public function annotateLastPage(string $pdfContent, string $qrPngContent, string $text, array $options = []): string
    {
        $qrSizePt = $options['qr_size_pt'] ?? 80;
        $marginPt = $options['margin_pt'] ?? 24;
        $font     = $options['font'] ?? 'Helvetica';
        $fontSize = $options['font_size'] ?? 9;
        $lineHeight = $options['line_height'] ?? 12;
        $gapPt    = $options['gap_pt'] ?? 8;

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

                $textWidth  = $pageWidth - (2 * $marginPt);
                $lines      = $this->wrapTextLines($pdf, $text, $textWidth);
                $textHeight = max(count($lines), 1) * $lineHeight;

                $qrX = $pageWidth - $marginPt - $qrSizePt;
                $qrY = $pageHeight - $marginPt - $qrSizePt - $textHeight - $gapPt;
                if ($qrY < $marginPt) {
                    $qrY = $marginPt;
                }

                $pdf->Image($qr, $qrX, $qrY, $qrSizePt, $qrSizePt, 'PNG');

                $textY = $qrY + $qrSizePt + $gapPt;
                if ($textY + $textHeight > $pageHeight - $marginPt) {
                    $textY = $pageHeight - $marginPt - $textHeight;
                }

                $pdf->SetXY($marginPt, $textY);
                $pdf->SetTextColor(0, 0, 0);
                foreach ($lines as $line) {
                    $pdf->Cell($textWidth, $lineHeight, $line, 0, 1, 'L');
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
