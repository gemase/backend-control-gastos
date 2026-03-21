<?php

namespace App\Http\Controllers\Periodo;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\UseCases\Periodo\MovimientosPeriodoUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MovimientosPeriodoController extends Controller
{
    public function __construct(
        private readonly MovimientosPeriodoUseCase $useCase
    ) {}

    public function __invoke(Request $request, int $id)
    {
        try {
            $movimientos = $this->useCase->execute($id, $request->user()->id);
            return response()->json(['status' => true, 'data' => $movimientos], Response::HTTP_OK);
        } catch (AppException $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], $e->getStatusCode());
        }
    }
}
