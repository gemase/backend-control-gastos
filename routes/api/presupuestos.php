<?php

use App\Http\Controllers\Presupuesto\ActualizarPresupuestoController;
use App\Http\Controllers\Presupuesto\ConsultarPresupuestoPorIdController;
use App\Http\Controllers\Presupuesto\ListarPresupuestosController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    /**
     * Devuelve presupuestos.
     */
    Route::get('/presupuestos', ListarPresupuestosController::class);

    /**
     * Devuelve un presupuesto de manera individual.
     */
    Route::get('/presupuestos/{id}', ConsultarPresupuestoPorIdController::class);

    /**
     * Editar un presupuesto.
     */
    Route::put('/presupuestos/{id}', ActualizarPresupuestoController::class);
});
