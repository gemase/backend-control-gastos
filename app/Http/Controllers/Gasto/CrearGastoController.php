<?php

namespace App\Http\Controllers\Gasto;

use App\Http\Controllers\Controller;
use App\Http\Requests\Gasto\CrearGastoRequest;
use App\UseCases\Gasto\CrearGastoUseCase;
use Illuminate\Http\Response;

class CrearGastoController extends Controller
{
    public function __construct(
        private readonly CrearGastoUseCase $useCase
    ) {}

    public function __invoke(CrearGastoRequest $request)
    {
        $gasto = $this->useCase->execute($request->user()->id, $request->validated());
        return response()->json(['status' => true, 'data' => $gasto], Response::HTTP_OK);
    }
}
