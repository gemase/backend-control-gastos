<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\User;

class EloquentUserRepository implements UserRepositoryInterface
{
    /**
     * Crea un nuevo usuario con los datos proporcionados.
     * @param array $data Datos del nuevo usuario.
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Busca un usuario por su dirección de correo electrónico.
     * @param string $email Correo electrónico a buscar.
     * @return User|null
     */
    public function buscarPorEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}
