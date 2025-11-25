<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContratoControllerTest extends TestCase
{
    /**
     * Test that the endpoint validates required fields.
     *
     * @return void
     */
    public function test_generar_contrato_pdf_validates_required_fields()
    {
        $response = $this->postJson('/api/generar-contrato-pdf', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'nombre_proveedor',
            'nombre_representante_legal',
            'fecha',
            'folio',
            'numero_contrato',
        ]);
    }

    /**
     * Test that the endpoint generates a PDF with valid data.
     *
     * @return void
     */
    public function test_generar_contrato_pdf_returns_pdf()
    {
        $response = $this->postJson('/api/generar-contrato-pdf', [
            'nombre_proveedor' => 'CENTRO DE EDUCACION INTEGRAL BRISALDY S. C.',
            'nombre_representante_legal' => 'Juan Pérez García',
            'fecha' => '13 de septiembre de 2021',
            'folio' => '4279-2021',
            'numero_contrato' => 'CONT-2026-001',
        ]);

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        $response->assertHeader('content-disposition', 'attachment; filename="contrato_4279-2021.pdf"');
    }

    /**
     * Test that partial data is rejected.
     *
     * @return void
     */
    public function test_generar_contrato_pdf_rejects_partial_data()
    {
        $response = $this->postJson('/api/generar-contrato-pdf', [
            'nombre_proveedor' => 'Test Provider',
            'fecha' => '13 de septiembre de 2021',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'nombre_representante_legal',
            'folio',
            'numero_contrato',
        ]);
    }

    /**
     * Test that empty strings are rejected.
     *
     * @return void
     */
    public function test_generar_contrato_pdf_rejects_empty_strings()
    {
        $response = $this->postJson('/api/generar-contrato-pdf', [
            'nombre_proveedor' => '',
            'nombre_representante_legal' => '',
            'fecha' => '',
            'folio' => '',
            'numero_contrato' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'nombre_proveedor',
            'nombre_representante_legal',
            'fecha',
            'folio',
            'numero_contrato',
        ]);
    }
}
