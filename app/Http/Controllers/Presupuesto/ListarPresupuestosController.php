<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use App\UseCases\Presupuesto\ListarPresupuestosUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ListarPresupuestosController extends Controller
{
    public function __construct(
        private readonly ListarPresupuestosUseCase $useCase
    ) {}

    public function __invoke(Request $request)
    {
        $presupuestos = $this->useCase->execute($request->user()->id);
        return response()->json(['status' => true, 'data' => $presupuestos], Response::HTTP_OK);
    }
}
