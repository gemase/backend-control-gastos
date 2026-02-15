<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;

Route::prefix('v1')->group(function () {
    /**
     * Crear un nuevo usuario de forma pública.
     */
    Route::post('/usuarios/crea-publico', [UsuarioController::class, 'creaPublico']);

    /**
     * Login de usuario.
     */
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    /**
     * Logout de usuario.
     */
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});
