<?php

namespace App\Http\Controllers\FormaPago;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\Http\Requests\FormaPago\EditarEstatusFormaPagoRequest;
use App\UseCases\FormaPago\ActualizarEstatusFormaPagoUseCase;
use Illuminate\Http\Response;

class ActualizarEstatusFormaPagoController extends Controller
{
    public function __construct(
        private readonly ActualizarEstatusFormaPagoUseCase $useCase
    ) {}

    public function __invoke(EditarEstatusFormaPagoRequest $request, int $id)
    {
        try {
            $formaPago = $this->useCase->execute($id, $request->user()->id, $request->validated());
            return response()->json(['status' => true, 'data' => $formaPago], Response::HTTP_OK);
        } catch (AppException $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], $e->getStatusCode());
        }
    }
}
