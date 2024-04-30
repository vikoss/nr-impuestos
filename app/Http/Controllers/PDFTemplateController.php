<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Mappers\TaxMapper;

class PDFTemplateController extends Controller
{
    public function qrCode(Request $request)
    {
        $tax = TaxMapper::addFolio(Tax::find($request->taxId), $request->taxType);
        $qrCode = base64_encode(
            QrCode::size(90)->generate(url("/{$tax->SLUG}/{$tax->uuid}/"))
        );
        $stringCode = "{$request->taxType}{$tax->FOLIO}|{$tax->CLAVE_CAT}|{$tax->FECHA_EMISION}|{$tax->uuid}|".base64_encode($tax->NOMBRE);
        $pdf = PDF::loadView('templates.qrCode', compact('qrCode', 'stringCode'));

        return $pdf->output();

    }
}
