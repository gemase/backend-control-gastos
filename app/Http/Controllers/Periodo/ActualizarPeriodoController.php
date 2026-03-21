<?php

namespace App\Http\Controllers\Periodo;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Periodo\EditarPeriodoRequest;
use App\UseCases\Periodo\ActualizarPeriodoUseCase;
use Illuminate\Http\Response;

class ActualizarPeriodoController extends Controller
{
    public function __construct(
        private readonly ActualizarPeriodoUseCase $useCase
    ) {}

    public function __invoke(EditarPeriodoRequest $request, int $id)
    {
        try {
            $periodo = $this->useCase->execute($id, $request->user()->id, $request->validated());
            return response()->json(['status' => true, 'data' => $periodo], Response::HTTP_OK);
        } catch (AppException $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], $e->getStatusCode());
        }
    }
}
