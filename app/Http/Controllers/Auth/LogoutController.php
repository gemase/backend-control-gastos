<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\UseCases\Auth\LogoutUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LogoutController extends Controller
{
    public function __construct(
        private readonly LogoutUseCase $useCase
    ) {}

    public function __invoke(Request $request)
    {
        $this->useCase->execute($request);
        return response()->json(['status' => true, 'data' => null], Response::HTTP_OK);
    }
}
