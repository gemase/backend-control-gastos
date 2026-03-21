<?php

namespace App\Http\Controllers\Gasto;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Gasto\CancelarGastoRequest;
use App\UseCases\Gasto\CancelarGastoUseCase;
use Illuminate\Http\Response;

class CancelarGastoController extends Controller
{
    public function __construct(
        private readonly CancelarGastoUseCase $useCase
    ) {}

    public function __invoke(CancelarGastoRequest $request, int $id)
    {
        try {
            $gasto = $this->useCase->execute($id, $request->user()->id, $request->validated());
            return response()->json(['status' => true, 'data' => $gasto], Response::HTTP_OK);
        } catch (AppException $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], $e->getStatusCode());
        }
    }
}
