<?php

namespace App\Http\Controllers\Gasto;

use App\Http\Controllers\Controller;
use App\UseCases\Gasto\ListarGastosUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ListarGastosController extends Controller
{
    public function __construct(
        private readonly ListarGastosUseCase $useCase
    ) {}

    public function __invoke(Request $request)
    {
        $gastos = $this->useCase->execute($request->user()->id);
        return response()->json(['status' => true, 'data' => $gastos], Response::HTTP_OK);
    }
}
