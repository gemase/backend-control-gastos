<?php

use App\Http\Controllers\Categoria\ActualizarCategoriaController;
use App\Http\Controllers\Categoria\ActualizarEstatusCategoriaController;
use App\Http\Controllers\Categoria\ConsultarCategoriaPorIdController;
use App\Http\Controllers\Categoria\CrearCategoriaController;
use App\Http\Controllers\Categoria\ListarCategoriasController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    /**
     * Crear una nueva categoría.
     */
    Route::post('/categorias', CrearCategoriaController::class);

    /**
     * Devuelve categorías.
     */
    Route::get('/categorias', ListarCategoriasController::class);

    /**
     * Devuelve una categoría de manera individual.
     */
    Route::get('/categorias/{id}', ConsultarCategoriaPorIdController::class);

    /**
     * Editar una categoría.
     */
    Route::put('/categorias/{id}', ActualizarCategoriaController::class);

    /**
     * Editar el estatus de una categoría.
     */
    Route::patch('/categorias/{id}/estatus', ActualizarEstatusCategoriaController::class);
});
