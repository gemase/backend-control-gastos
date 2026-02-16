<?php

namespace App\Http\Controllers;

use App\Http\Requests\Usuario\UsuarioCreaPublicoRequest;
use App\Services\Usuario\CrearUsuarioService;
use Exception;
use Illuminate\Http\Response;

class UsuarioController extends Controller
{
    /**
     * Crear un nuevo usuario de forma pública.
     * @param UsuarioCreaPublicoRequest $request
     * @param CrearUsuarioService $crearUsuarioService
     * @return \Illuminate\Http\JsonResponse
     */
    public function creaPublico(UsuarioCreaPublicoRequest $request, CrearUsuarioService $crearUsuarioService)
    {
        try {
            $datosValidados = $request->validated();
            $usuario = $crearUsuarioService->ejecutar($datosValidados);
            return response()->json(['status' => true, 'data' => $usuario], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
