<?php

namespace App\Http\Controllers\Gasto;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Gasto\EditarGastoRequest;
use App\UseCases\Gasto\ActualizarGastoUseCase;
use Illuminate\Http\Response;

class ActualizarGastoController extends Controller
{
    public function __construct(
        private readonly ActualizarGastoUseCase $useCase
    ) {}

    public function __invoke(EditarGastoRequest $request, int $id)
    {
        try {
            $gasto = $this->useCase->execute($id, $request->user()->id, $request->validated());
            return response()->json(['status' => true, 'data' => $gasto], Response::HTTP_OK);
        } catch (AppException $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], $e->getStatusCode());
        }
    }
}
