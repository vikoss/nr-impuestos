<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerarContratoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nombre_proveedor' => 'required|string|max:255',
            'nombre_representante_legal' => 'required|string|max:255',
            'fecha' => 'required|string|max:100',
            'folio' => 'required|string|max:50',
            'numero_contrato' => 'required|string|max:100',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'nombre_proveedor.required' => 'El nombre del proveedor es requerido.',
            'nombre_representante_legal.required' => 'El nombre del representante legal es requerido.',
            'fecha.required' => 'La fecha es requerida.',
            'folio.required' => 'El folio es requerido.',
            'numero_contrato.required' => 'El número de contrato es requerido.',
        ];
    }
}
