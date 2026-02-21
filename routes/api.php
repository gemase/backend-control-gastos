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

    /**
     * Rutas de categorías de movimientos.
     */
    require __DIR__ . '/api/categorias.php';

    /**
     * Rutas de formas de pago.
     */
    require __DIR__ . '/api/formas_pago.php';

    /**
     * Rutas de periodos.
     */
    require __DIR__ . '/api/periodos.php';

    /**
     * Rutas de ingresos.
     */
    require __DIR__ . '/api/ingresos.php';
});
