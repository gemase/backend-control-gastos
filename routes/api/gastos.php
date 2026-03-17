<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GastoController;

Route::middleware('auth:sanctum')->group(function () {
    /**
     * Crear un nuevo gasto.
     */
    Route::post('/gastos', [GastoController::class, 'crearGasto']);

    /**
     * Devuelve gastos.

     */
    Route::get('/gastos', [GastoController::class, 'listarGastos']);

    /**
     * Devuelve un gasto de manera individual.
     */
    Route::get('/gastos/{id}', [GastoController::class, 'consultarGastoPorId']);

    /**
     * Editar un gasto.
     */
    Route::put('/gastos/{id}', [GastoController::class, 'actualizarGasto']);

    /**
     * Cancelar un gasto.
     */
    Route::patch('/gastos/{id}/cancelar', [GastoController::class, 'cancelarGasto']);
});
