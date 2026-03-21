<?php

namespace App\Http\Controllers\Periodo;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\UseCases\Periodo\DashboardPeriodoUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DashboardPeriodoController extends Controller
{
    public function __construct(
        private readonly DashboardPeriodoUseCase $useCase
    ) {}

    public function __invoke(Request $request, int $id)
    {
        try {
            $data = $this->useCase->execute($id, $request->user()->id);
            return response()->json(['status' => true, 'data' => $data], Response::HTTP_OK);
        } catch (AppException $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], $e->getStatusCode());
        }
    }
}
