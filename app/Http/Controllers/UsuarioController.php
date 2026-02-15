<?php

namespace App\Http\Controllers;

use App\Http\Requests\Usuario\UsuarioCreaPublicoRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;

class UsuarioController extends Controller
{
    /**
     * Crear un nuevo usuario de forma pública.
     * @param UsuarioCreaPublicoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function creaPublico(UsuarioCreaPublicoRequest $request)
    {
        try {
            $datosValidados = $request->validated();
            $usuario = User::create([
                'name' => $datosValidados['name'],
                'email' => $datosValidados['email'],
                'password' => bcrypt($datosValidados['password']),
            ]);
            return response()->json(['status' => true, 'data' => $usuario], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
