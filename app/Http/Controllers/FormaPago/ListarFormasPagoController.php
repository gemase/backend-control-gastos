<?php

namespace App\Http\Controllers\FormaPago;

use App\Http\Controllers\Controller;
use App\UseCases\FormaPago\ListarFormasPagoUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ListarFormasPagoController extends Controller
{
    public function __construct(
        private readonly ListarFormasPagoUseCase $useCase
    ) {}

    public function __invoke(Request $request)
    {
        $formasPago = $this->useCase->execute($request->user()->id);
        return response()->json(['status' => true, 'data' => $formasPago], Response::HTTP_OK);
    }
}
