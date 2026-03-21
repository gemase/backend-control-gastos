<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Usuario\CrearUsuarioController;

Route::prefix('v1')->group(function () {
    /**
     * Crear un nuevo usuario de forma pública.
     */
    Route::post('/usuarios/crea-publico', CrearUsuarioController::class);

    /**
     * Login de usuario.
     */
    Route::post('/login', LoginController::class)->name('login');

    /**
     * Logout de usuario.
     */
    Route::post('/logout', LogoutController::class)->middleware('auth:sanctum');

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

    /**
     * Rutas de gastos.
     */
    require __DIR__ . '/api/gastos.php';

    /**
     * Rutas de presupuestos.
     */
    require __DIR__ . '/api/presupuestos.php';
});
