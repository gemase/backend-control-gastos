<?php

use App\Http\Controllers\Ingreso\ActualizarEstatusIngresoController;
use App\Http\Controllers\Ingreso\ActualizarIngresoController;
use App\Http\Controllers\Ingreso\ConsultarIngresoPorIdController;
use App\Http\Controllers\Ingreso\CrearIngresoController;
use App\Http\Controllers\Ingreso\ListarIngresosController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    /**
     * Crear un nuevo ingreso.
     */
    Route::post('/ingresos', CrearIngresoController::class);

    /**
     * Devuelve ingresos.
     */
    Route::get('/ingresos', ListarIngresosController::class);

    /**
     * Devuelve un ingreso de manera individual.
     */
    Route::get('/ingresos/{id}', ConsultarIngresoPorIdController::class);

    /**
     * Editar un ingreso.
     */
    Route::put('/ingresos/{id}', ActualizarIngresoController::class);

    /**
     * Editar el estatus de un ingreso.
     */
    Route::patch('/ingresos/{id}/estatus', ActualizarEstatusIngresoController::class);
});
