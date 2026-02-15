<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;

Route::middleware('auth:sanctum')->group(function () {
    /**
     * Crear una nueva categoría de movimiento.
     */
    Route::post('/categorias', [CategoriaController::class, 'crearCategoria']);

    /**
     * Devuelve categorías de movimientos.
     */
    Route::get('/categorias', [CategoriaController::class, 'listarCategorias']);

    /**
     * Devuelve una categoría de movimiento de manera individual.
     */
    Route::get('/categorias/{id}', [CategoriaController::class, 'consultarCategoriaPorId']);

    /**
     * Editar una categoría de movimiento.
     */
    Route::put('/categorias/{id}', [CategoriaController::class, 'actualizarCategoria']);

    /**
     * Editar el estatus de una categoría de movimiento.
     */
    Route::patch('/categorias/{id}/estatus', [CategoriaController::class, 'actualizarEstatusCategoria']);
});
