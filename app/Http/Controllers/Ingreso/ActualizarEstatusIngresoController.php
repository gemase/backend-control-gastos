<?php

namespace App\Http\Controllers\Ingreso;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ingreso\EditarEstatusIngresoRequest;
use App\UseCases\Ingreso\ActualizarEstatusIngresoUseCase;
use Illuminate\Http\Response;

class ActualizarEstatusIngresoController extends Controller
{
    public function __construct(
        private readonly ActualizarEstatusIngresoUseCase $useCase
    ) {}

    public function __invoke(EditarEstatusIngresoRequest $request, int $id)
    {
        try {
            $ingreso = $this->useCase->execute($id, $request->user()->id, $request->validated());
            return response()->json(['status' => true, 'data' => $ingreso], Response::HTTP_OK);
        } catch (AppException $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], $e->getStatusCode());
        }
    }
}
