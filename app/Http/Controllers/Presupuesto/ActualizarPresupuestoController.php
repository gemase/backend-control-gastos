<?php

namespace App\Http\Controllers\Presupuesto;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Presupuesto\EditarPresupuestoRequest;
use App\UseCases\Presupuesto\ActualizarPresupuestoUseCase;
use Illuminate\Http\Response;

class ActualizarPresupuestoController extends Controller
{
    public function __construct(
        private readonly ActualizarPresupuestoUseCase $useCase
    ) {}

    public function __invoke(EditarPresupuestoRequest $request, int $id)
    {
        try {
            $presupuesto = $this->useCase->execute($id, $request->user()->id, $request->validated());
            return response()->json(['status' => true, 'data' => $presupuesto], Response::HTTP_OK);
        } catch (AppException $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], $e->getStatusCode());
        }
    }
}
