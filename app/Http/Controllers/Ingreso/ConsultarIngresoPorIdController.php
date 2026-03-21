<?php

namespace App\Http\Controllers\Ingreso;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\UseCases\Ingreso\ConsultarIngresoPorIdUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ConsultarIngresoPorIdController extends Controller
{
    public function __construct(
        private readonly ConsultarIngresoPorIdUseCase $useCase
    ) {}

    public function __invoke(Request $request, int $id)
    {
        try {
            $ingreso = $this->useCase->execute($id, $request->user()->id);
            return response()->json(['status' => true, 'data' => $ingreso], Response::HTTP_OK);
        } catch (AppException $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], $e->getStatusCode());
        }
    }
}
