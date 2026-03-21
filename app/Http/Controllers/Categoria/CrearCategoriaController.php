<?php

namespace App\Http\Controllers\Categoria;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Categoria\CreaCategoriaRequest;
use App\UseCases\Categoria\CrearCategoriaUseCase;
use Illuminate\Http\Response;

class CrearCategoriaController extends Controller
{
    public function __construct(
        private readonly CrearCategoriaUseCase $useCase
    ) {}

    public function __invoke(CreaCategoriaRequest $request)
    {
        try {
            $categoria = $this->useCase->execute($request->user()->id, $request->validated());
            return response()->json(['status' => true, 'data' => $categoria], Response::HTTP_OK);
        } catch (AppException $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], $e->getStatusCode());
        }
    }
}
