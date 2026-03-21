<?php

namespace App\Http\Controllers\Ingreso;

use App\Http\Controllers\Controller;
use App\UseCases\Ingreso\ListarIngresosUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ListarIngresosController extends Controller
{
    public function __construct(
        private readonly ListarIngresosUseCase $useCase
    ) {}

    public function __invoke(Request $request)
    {
        $ingresos = $this->useCase->execute($request->user()->id);
        return response()->json(['status' => true, 'data' => $ingresos], Response::HTTP_OK);
    }
}
