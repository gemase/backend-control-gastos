<?php

namespace App\UseCases\Auth;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\Auth;

class LoginUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ) {}

    public function execute(array $datos): array
    {
        if (!Auth::attempt(['email' => $datos['email'], 'password' => $datos['password']])) {
            throw new BusinessException('El correo electrónico y/o contraseña no son válidos.');
        }

        $usuario = $this->repository->buscarPorEmail($datos['email']);
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return [
            'usuario' => $usuario,
            'token' => $token,
            'tipo_token' => 'Bearer',
        ];
    }
}
