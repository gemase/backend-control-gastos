<?php

use App\Http\Controllers\FormaPago\ActualizarEstatusFormaPagoController;
use App\Http\Controllers\FormaPago\ActualizarFormaPagoController;
use App\Http\Controllers\FormaPago\ConsultarFormaPagoPorIdController;
use App\Http\Controllers\FormaPago\CrearFormaPagoController;
use App\Http\Controllers\FormaPago\ListarFormasPagoController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    /**
     * Crear una nueva forma de pago.
     */
    Route::post('/formas-pago', CrearFormaPagoController::class);

    /**
     * Editar una forma de pago.
     */
    Route::put('/formas-pago/{id}', ActualizarFormaPagoController::class);

    /**
     * Editar el estatus de una forma de pago.
     */
    Route::patch('/formas-pago/{id}/estatus', ActualizarEstatusFormaPagoController::class);

    /**
     * Devuelve todas las formas de pago.
     */
    Route::get('/formas-pago', ListarFormasPagoController::class);

    /**
     * Devuelve una forma de pago de manera individual.
     */
    Route::get('/formas-pago/{id}', ConsultarFormaPagoPorIdController::class);
});
