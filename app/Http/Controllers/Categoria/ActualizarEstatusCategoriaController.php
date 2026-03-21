<?php

namespace App\Http\Controllers\Categoria;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Categoria\EditaEstatusCategoriaRequest;
use App\UseCases\Categoria\ActualizarEstatusCategoriaUseCase;
use Illuminate\Http\Response;

class ActualizarEstatusCategoriaController extends Controller
{
    public function __construct(
        private readonly ActualizarEstatusCategoriaUseCase $useCase
    ) {}

    public function __invoke(EditaEstatusCategoriaRequest $request, int $id)
    {
        try {
            $categoria = $this->useCase->execute($id, $request->user()->id, $request->validated());
            return response()->json(['status' => true, 'data' => $categoria], Response::HTTP_OK);
        } catch (AppException $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], $e->getStatusCode());
        }
    }
}
