<?php

namespace App\Contracts\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * Crea un nuevo usuario con los datos proporcionados.
     * @param array $data Datos del nuevo usuario.
     * @return User
     */
    public function create(array $data): User;

    /**
     * Busca un usuario por su dirección de correo electrónico.
     * @param string $email Correo electrónico a buscar.
     * @return User|null
     */
    public function buscarPorEmail(string $email): ?User;
}
