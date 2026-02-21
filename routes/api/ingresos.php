<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IngresoController;

Route::middleware('auth:sanctum')->group(function () {
    /**
     * Crear un nuevo ingreso.
     */
    Route::post('/ingresos', [IngresoController::class, 'crearIngreso']);

    /**
     * Devuelve ingresos.

     */
    Route::get('/ingresos', [IngresoController::class, 'listarIngresos']);

    /**
     * Devuelve un ingreso de manera individual.
     */
    Route::get('/ingresos/{id}', [IngresoController::class, 'consultarIngresoPorId']);

    /**
     * Editar un ingreso.
     */
    Route::put('/ingresos/{id}', [IngresoController::class, 'actualizarIngreso']);

    /**
     * Editar el estatus de un ingreso.
     */
    Route::patch('/ingresos/{id}/estatus', [IngresoController::class, 'actualizarEstatusIngreso']);
});
