<?php

namespace App\Http\Controllers\Presupuesto;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\UseCases\Presupuesto\ConsultarPresupuestoPorIdUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ConsultarPresupuestoPorIdController extends Controller
{
    public function __construct(
        private readonly ConsultarPresupuestoPorIdUseCase $useCase
    ) {}

    public function __invoke(Request $request, int $id)
    {
        try {
            $presupuesto = $this->useCase->execute($id, $request->user()->id);
            return response()->json(['status' => true, 'data' => $presupuesto], Response::HTTP_OK);
        } catch (AppException $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], $e->getStatusCode());
        }
    }
}
