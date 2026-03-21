<?php

namespace App\Http\Controllers\FormaPago;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\UseCases\FormaPago\ConsultarFormaPagoPorIdUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ConsultarFormaPagoPorIdController extends Controller
{
    public function __construct(
        private readonly ConsultarFormaPagoPorIdUseCase $useCase
    ) {}

    public function __invoke(Request $request, int $id)
    {
        try {
            $formaPago = $this->useCase->execute($id, $request->user()->id);
            return response()->json(['status' => true, 'data' => $formaPago], Response::HTTP_OK);
        } catch (AppException $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], $e->getStatusCode());
        }
    }
}
