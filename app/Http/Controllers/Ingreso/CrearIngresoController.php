<?php

namespace App\Http\Controllers\Ingreso;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ingreso\CrearIngresoRequest;
use App\UseCases\Ingreso\CrearIngresoUseCase;
use Illuminate\Http\Response;

class CrearIngresoController extends Controller
{
    public function __construct(
        private readonly CrearIngresoUseCase $useCase
    ) {}

    public function __invoke(CrearIngresoRequest $request)
    {
        $ingreso = $this->useCase->execute($request->user()->id, $request->validated());
        return response()->json(['status' => true, 'data' => $ingreso], Response::HTTP_OK);
    }
}
