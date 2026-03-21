<?php

namespace App\Http\Controllers\Gasto;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\UseCases\Gasto\ConsultarGastoPorIdUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ConsultarGastoPorIdController extends Controller
{
    public function __construct(
        private readonly ConsultarGastoPorIdUseCase $useCase
    ) {}

    public function __invoke(Request $request, int $id)
    {
        try {
            $gasto = $this->useCase->execute($id, $request->user()->id);
            return response()->json(['status' => true, 'data' => $gasto], Response::HTTP_OK);
        } catch (AppException $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], $e->getStatusCode());
        }
    }
}
