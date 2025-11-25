<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\PDFTemplateController;
use App\Http\Controllers\TaxController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout');
    Route::post('/refresh', 'refresh');
});

Route::controller(TaxController::class)->group(function () {
    Route::get('/taxes', 'index');
    Route::get('/taxes/{tax}', 'show');
    Route::get('/taxes/{uuid}/type/{type}', 'validity');
    Route::post('/taxes', 'store');
    Route::delete('/taxes/{tax}', 'destroy');
});

Route::controller(PDFTemplateController::class)->group(function () {
    Route::post('/templates/qr-code', 'qrCode');
});

Route::controller(ContratoController::class)->group(function () {
    Route::post('/generar-contrato-pdf', 'generarContratoPdf');
});
