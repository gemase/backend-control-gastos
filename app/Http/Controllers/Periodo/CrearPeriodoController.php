<?php

namespace App\Http\Controllers\Periodo;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Periodo\CrearPeriodoRequest;
use App\UseCases\Periodo\CrearPeriodoUseCase;
use Illuminate\Http\Response;

class CrearPeriodoController extends Controller
{
    public function __construct(
        private readonly CrearPeriodoUseCase $useCase
    ) {}

    public function __invoke(CrearPeriodoRequest $request)
    {
        try {
            $periodo = $this->useCase->execute($request->user()->id, $request->validated());
            return response()->json(['status' => true, 'data' => $periodo], Response::HTTP_OK);
        } catch (AppException $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], $e->getStatusCode());
        }
    }
}
