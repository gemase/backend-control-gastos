<?php

namespace App\Http\Controllers\FormaPago;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\Http\Requests\FormaPago\CrearFormaPagoRequest;
use App\UseCases\FormaPago\CrearFormaPagoUseCase;
use Illuminate\Http\Response;

class CrearFormaPagoController extends Controller
{
    public function __construct(
        private readonly CrearFormaPagoUseCase $useCase
    ) {}

    public function __invoke(CrearFormaPagoRequest $request)
    {
        try {
            $formaPago = $this->useCase->execute($request->user()->id, $request->validated());
            return response()->json(['status' => true, 'data' => $formaPago], Response::HTTP_OK);
        } catch (AppException $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], $e->getStatusCode());
        }
    }
}
