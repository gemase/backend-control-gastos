<?php

use App\Http\Controllers\Gasto\ActualizarGastoController;
use App\Http\Controllers\Gasto\CancelarGastoController;
use App\Http\Controllers\Gasto\ConsultarGastoPorIdController;
use App\Http\Controllers\Gasto\CrearGastoController;
use App\Http\Controllers\Gasto\ListarGastosController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    /**
     * Crear un nuevo gasto.
     */
    Route::post('/gastos', CrearGastoController::class);

    /**
     * Devuelve gastos.
     */
    Route::get('/gastos', ListarGastosController::class);

    /**
     * Devuelve un gasto de manera individual.
     */
    Route::get('/gastos/{id}', ConsultarGastoPorIdController::class);

    /**
     * Editar un gasto.
     */
    Route::put('/gastos/{id}', ActualizarGastoController::class);

    /**
     * Cancelar un gasto.
     */
    Route::patch('/gastos/{id}/cancelar', CancelarGastoController::class);
});
