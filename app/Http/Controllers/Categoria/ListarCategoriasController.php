<?php

namespace App\Http\Controllers\Categoria;

use App\Http\Controllers\Controller;
use App\UseCases\Categoria\ListarCategoriasUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ListarCategoriasController extends Controller
{
    public function __construct(
        private readonly ListarCategoriasUseCase $useCase
    ) {}

    public function __invoke(Request $request)
    {
        $categorias = $this->useCase->execute($request->user()->id);
        return response()->json(['status' => true, 'data' => $categorias], Response::HTTP_OK);
    }
}
