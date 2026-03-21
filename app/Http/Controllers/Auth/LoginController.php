<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\UseCases\Auth\LoginUseCase;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    public function __construct(
        private readonly LoginUseCase $useCase
    ) {}

    public function __invoke(LoginRequest $request)
    {
        try {
            $data = $this->useCase->execute($request->validated());
            return response()->json(['status' => true, 'data' => $data], Response::HTTP_OK);
        } catch (AppException $e) {
            return response()->json(['status' => false, 'error' => ['message' => $e->getMessage()]], $e->getStatusCode());
        }
    }
}
