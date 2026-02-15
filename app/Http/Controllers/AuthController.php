<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Login de usuario.
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                throw new Exception('El correo electrónico y/o contraseña no son válidos.');
            }
            $usuario = User::where('email', $request->input('email'))->first();
            if (!$usuario) {
                throw new Exception('El correo electrónico y/o contraseña no son válidos.');
            }
            $token = $usuario->createToken('auth_token')->plainTextToken;
            return response()->json(['status' => true, 'data' => ['usuario' => $usuario, 'token' => $token, 'tipo_token' => 'Bearer']], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['status' => true, 'data' => null], Response::HTTP_OK);
    }
}
