<?php

use App\Http\Controllers\Periodo\ActualizarPeriodoController;
use App\Http\Controllers\Periodo\ConsultarPeriodoPorIdController;
use App\Http\Controllers\Periodo\CrearPeriodoController;
use App\Http\Controllers\Periodo\DashboardPeriodoController;
use App\Http\Controllers\Periodo\ListarPeriodosController;
use App\Http\Controllers\Periodo\MovimientosPeriodoController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    /**
     * Crear un nuevo periodo.
     */
    Route::post('/periodos', CrearPeriodoController::class);

    /**
     * Devuelve periodos.
     */
    Route::get('/periodos', ListarPeriodosController::class);

    /**
     * Devuelve un periodo de manera individual.
     */
    Route::get('/periodos/{id}', ConsultarPeriodoPorIdController::class);

    /**
     * Editar un periodo.
     */
    Route::put('/periodos/{id}', ActualizarPeriodoController::class);

    /**
     * Dashboard del periodo.
     */
    Route::get('/periodos/{id}/dashboard', DashboardPeriodoController::class);

    /**
     * Movimientos del periodo.
     */
    Route::get('/periodos/{id}/movimientos', MovimientosPeriodoController::class);
});
