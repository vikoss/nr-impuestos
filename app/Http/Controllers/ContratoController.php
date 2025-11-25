<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerarContratoRequest;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ContratoController extends Controller
{
    /**
     * Generate a PDF contract based on the provided data.
     *
     * @return \Illuminate\Http\Response
     */
    public function generarContratoPdf(GenerarContratoRequest $request)
    {
        $data = $request->validated();

        // Generate QR code based on numero_contrato
        $qrCode = base64_encode(
            QrCode::format('svg')->size(100)->generate($data['numero_contrato'])
        );

        // Prepare data for PDF view
        $pdfData = [
            'nombre_proveedor' => $data['nombre_proveedor'],
            'nombre_representante_legal' => $data['nombre_representante_legal'],
            'fecha' => $data['fecha'],
            'folio' => $data['folio'],
            'numero_contrato' => $data['numero_contrato'],
            'qr_code' => $qrCode,
        ];

        // Generate PDF
        $pdf = PDF::loadView('pdf.contrato', $pdfData);
        $pdf->setPaper('letter', 'portrait');

        // Return PDF as download
        $filename = "contrato_{$data['folio']}.pdf";

        return $pdf->download($filename);
    }
}
