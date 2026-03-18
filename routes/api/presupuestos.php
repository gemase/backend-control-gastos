<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PresupuestoController;

Route::middleware('auth:sanctum')->group(function () {
    /**
     * Devuelve presupuestos.
     */
    Route::get('/presupuestos', [PresupuestoController::class, 'listarPresupuestos']);

    /**
     * Devuelve un presupuesto de manera individual.
     */
    Route::get('/presupuestos/{id}', [PresupuestoController::class, 'consultarPresupuestoPorId']);

    /**
     * Editar un presupuesto.
     */
    Route::put('/presupuestos/{id}', [PresupuestoController::class, 'actualizarPresupuesto']);
});
