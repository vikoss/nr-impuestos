<?php

namespace App\Mappers;

use App\Models\Tax;

class TaxMapper
{
    public static function addFolio(Tax $tax, string $type)
    {
        if ($type == 'CV' || $type == 'clave-y-valor-catastral') {
            $tax->FOLIO = $tax->CLAVE_Y_VALOR_CATASTRAL;
            $tax->CERTIFICACION = 'Clave y Valor Catastral';
            $tax->SLUG = 'clave-y-valor-catastral';
        }
        if ($type == 'PP' || $type == 'pago-predial') {
            $tax->FOLIO = $tax->NO_ADEUDO_PREDIAL;
            $tax->CERTIFICACION = 'Pago Predial';
            $tax->SLUG = 'pago-predial';
        }
        if ($type == 'AM' || $type == 'aportaciones-a-mejoras') {
            $tax->FOLIO = $tax->APORTACIONES_MEJORAS;
            $tax->CERTIFICACION = 'Aportaciones a mejoras';
            $tax->SLUG = 'aportaciones-a-mejoras';
        }

        return $tax;
    }
}