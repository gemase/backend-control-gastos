<?php

namespace App\Http\Controllers\Periodo;

use App\Http\Controllers\Controller;
use App\UseCases\Periodo\ListarPeriodosUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ListarPeriodosController extends Controller
{
    public function __construct(
        private readonly ListarPeriodosUseCase $useCase
    ) {}

    public function __invoke(Request $request)
    {
        $periodos = $this->useCase->execute($request->user()->id);
        return response()->json(['status' => true, 'data' => $periodos], Response::HTTP_OK);
    }
}
