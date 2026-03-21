<?php

namespace App\Http\Controllers\Categoria;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\UseCases\Categoria\ConsultarCategoriaPorIdUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ConsultarCategoriaPorIdController extends Controller
{
    public function __construct(
        private readonly ConsultarCategoriaPorIdUseCase $useCase
    ) {}

    public function __invoke(Request $request, int $id)
    {
        try {
            $categoria = $this->useCase->execute($id, $request->user()->id);
            return response()->json(['status' => true, 'data' => $categoria], Response::HTTP_OK);
        } catch (AppException $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], $e->getStatusCode());
        }
    }
}
