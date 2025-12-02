<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\ProviderSignature;
use App\Services\PdfSigner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PdfSignatureController extends Controller
{
    private PdfSigner $pdfSigner;

    public function __construct(PdfSigner $pdfSigner)
    {
        $this->pdfSigner = $pdfSigner;
    }

    /**
     * POST /api/sign-pdf
     * Requires JWT auth. Accepts multipart/form-data with 'file' (PDF).
     * Returns JSON with base64-encoded annotated PDF and metadata.
     */
    public function sign(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => ['required', 'file', 'mimes:pdf', 'max:20480'], // 20MB
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid request', 'errors' => $validator->errors()], 422);
        }

        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        /** @var Provider|null $provider */
        $provider = $user->provider;
        if (!$provider) {
            return response()->json(['message' => 'Provider not found for user. Create your provider profile first.'], 404);
        }

        $pdfBinary = file_get_contents($request->file('file')->getRealPath());

        // Signature ID as UUID v4
        $uuid = (string) Str::uuid();
        //$qrUrl = 'https://tuempresa.com/qr/' . $uuid;
        $qrUrl = url("/qr/{$uuid}/");

        // Generate QR as PNG
        $qrPng = QrCode::format('png')->size(300)->margin(0)->generate($qrUrl);

        // Determine text to stamp (default sample if not provided)
        $originalString = 'Cadena Original Sello: || ' . $provider->date . '|' . $provider->rfc . '|' . $provider->contract_number . '|' . $provider->folio . '||';
        $digitalSeal = 'Sello Digital: ' . base64_encode($originalString);
        $text = trim($originalString . "\n" . $digitalSeal);

        // Annotate last page
        $annotated = $this->pdfSigner->annotateLastPage($pdfBinary, $qrPng, $text, [
            'qr_size_pt' => 70,
            'margin_pt' => 130,
            'font' => 'Helvetica',
            'font_size' => 7,
            'line_height' => 10,
        ]);

        // Compute hash for integrity (optional)
        $pdfHash = hash('sha256', $annotated);

        // Persist signature record
        $signature = ProviderSignature::create([
            'provider_id' => $provider->id,
            'user_id' => $user->id,
            'uuid' => $uuid,
            'original_string' => $originalString,
            'digital_seal' => $digitalSeal,
            'pdf_hash' => $pdfHash,
            'signed_at' => now(),
        ]);

        // Return base64
        $base64 = base64_encode($annotated);

        return response()->json([
            'pdf_base64' => $base64,
            'uuid' => $uuid,
            'qr_url' => $qrUrl,
            'provider_id' => $provider->id,
            'pdf_hash' => $pdfHash,
            'signed_at' => $signature->signed_at?->toIso8601String(),
        ]);
    }

    /**
     * GET /api/qr/{uuid}
     * Public verification endpoint; returns provider and signature details.
     */
    public function show(string $uuid)
    {
        $signature = ProviderSignature::with('provider')->where('uuid', $uuid)->first();
        if (!$signature) {
            return response()->json(['message' => 'Signature not found'], 404);
        }

        $provider = $signature->provider;

        return response()->json([
            'uuid' => $signature->uuid,
            'signed_at' => optional($signature->signed_at)->toIso8601String(),
            'pdf_hash' => $signature->pdf_hash,
            'original_string' => $signature->original_string,
            'digital_seal' => $signature->digital_seal,
            'provider' => [
                'name' => $provider->name,
                'legal_representative' => $provider->legal_representative,
                'date' => optional($provider->date)->toIso8601String(),
                'folio' => $provider->folio,
                'contract_number' => $provider->contract_number,
                'rfc' => $provider->rfc,
            ],
            'status' => 'valid',
        ]);
    }
}
