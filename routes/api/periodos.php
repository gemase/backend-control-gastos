<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeriodoController;

Route::middleware('auth:sanctum')->group(function () {
    /**
     * Crear un nuevo periodo.
     */
    Route::post('/periodos', [PeriodoController::class, 'crearPeriodo']);

    /**
     * Devuelve periodos.

     */
    Route::get('/periodos', [PeriodoController::class, 'listarPeriodos']);

    /**
     * Devuelve un periodo de manera individual.
     */
    Route::get('/periodos/{id}', [PeriodoController::class, 'consultarPeriodoPorId']);

    /**
     * Editar un periodo.
     */
    Route::put('/periodos/{id}', [PeriodoController::class, 'actualizarPeriodo']);

    /**
     * Dashboard del periodo.
     */
    Route::get('/periodos/{id}/dashboard', [PeriodoController::class, 'dashboard']);

    /**
     * Movimientos paginados del periodo.
     */
    Route::get('/periodos/{id}/movimientos', [PeriodoController::class, 'movimientos']);
});
