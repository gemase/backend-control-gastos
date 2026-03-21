<?php

namespace App\Http\Controllers\Categoria;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Categoria\EditaCategoriaRequest;
use App\UseCases\Categoria\ActualizarCategoriaUseCase;
use Illuminate\Http\Response;

class ActualizarCategoriaController extends Controller
{
    public function __construct(
        private readonly ActualizarCategoriaUseCase $useCase
    ) {}

    public function __invoke(EditaCategoriaRequest $request, int $id)
    {
        try {
            $categoria = $this->useCase->execute($id, $request->user()->id, $request->validated());
            return response()->json(['status' => true, 'data' => $categoria], Response::HTTP_OK);
        } catch (AppException $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], $e->getStatusCode());
        }
    }
}
