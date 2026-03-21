<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Http\Requests\Usuario\UsuarioCreaPublicoRequest;
use App\UseCases\Usuario\CrearUsuarioUseCase;
use Illuminate\Http\Response;

class CrearUsuarioController extends Controller
{
    public function __construct(
        private readonly CrearUsuarioUseCase $useCase
    ) {}

    public function __invoke(UsuarioCreaPublicoRequest $request)
    {
        $usuario = $this->useCase->execute($request->validated());
        return response()->json(['status' => true, 'data' => $usuario], Response::HTTP_OK);
    }
}
