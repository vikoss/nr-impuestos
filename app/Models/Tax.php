<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'EXP',
        'CLAVE_Y_VALOR_CATASTRAL',
        'NO_ADEUDO_PREDIAL',
        'APORTACIONES_MEJORAS',
        'ESTADO',
        'CLAVE_CAT',
        'NOMBRE',
        'PAGO_PREDIO',
        'FECHA_DE_PAGO_PREDIAL',
        'PAGO_CERT_CATASTRO',
        'FECHA_DE_PAGO_CERTIFICACION',
        'PAGO_NO_ADEUDO' ,
        'FECHA_DE_PAGO_NO_ADEUDO',
        'PAGO_MEJORAS',
        'FECHA_DE_PAGO_MEJORAS',
        'FECHA_EMISION',
        'VIGENCIA',
    ];

    /**
     * Scope a query to filter by inventory number.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopefilterByExpediente($query, $expediente)
    {
        if ($expediente) {
            return $query->where('EXP', 'like', "%{$expediente}%");
        }

        return $query;
    }
}
