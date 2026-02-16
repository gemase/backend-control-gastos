<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormaPagoController;

Route::middleware('auth:sanctum')->group(function () {
    /**
     * Crear una nueva forma de pago.
     */
    Route::post('/formas-pago', [FormaPagoController::class, 'crearFormaPago']);

    /**
     * Editar una forma de pago.
     */
    Route::put('/formas-pago/{id}', [FormaPagoController::class, 'actualizarFormaPago']);

    /**
     * Editar el estatus de una forma de pago.
     */
    Route::patch('/formas-pago/{id}/estatus', [FormaPagoController::class, 'actualizarEstatusFormaPago']);

    /**
     * Devuelve todas las formas de pago.
     */
    Route::get('/formas-pago', [FormaPagoController::class, 'listarFormasPago']);

    /**
     * Devuelve una forma de pago de manera individual.
     */
    Route::get('/formas-pago/{id}', [FormaPagoController::class, 'consultarFormaPagoPorId']);
});
