<?php

namespace App\Http\Controllers\Periodo;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\UseCases\Periodo\ConsultarPeriodoPorIdUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ConsultarPeriodoPorIdController extends Controller
{
    public function __construct(
        private readonly ConsultarPeriodoPorIdUseCase $useCase
    ) {}

    public function __invoke(Request $request, int $id)
    {
        try {
            $periodo = $this->useCase->execute($id, $request->user()->id);
            return response()->json(['status' => true, 'data' => $periodo], Response::HTTP_OK);
        } catch (AppException $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], $e->getStatusCode());
        }
    }
}
