<?php

namespace App\Http\Controllers\Ingreso;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ingreso\EditarIngresoRequest;
use App\UseCases\Ingreso\ActualizarIngresoUseCase;
use Illuminate\Http\Response;

class ActualizarIngresoController extends Controller
{
    public function __construct(
        private readonly ActualizarIngresoUseCase $useCase
    ) {}

    public function __invoke(EditarIngresoRequest $request, int $id)
    {
        try {
            $ingreso = $this->useCase->execute($id, $request->user()->id, $request->validated());
            return response()->json(['status' => true, 'data' => $ingreso], Response::HTTP_OK);
        } catch (AppException $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], $e->getStatusCode());
        }
    }
}
