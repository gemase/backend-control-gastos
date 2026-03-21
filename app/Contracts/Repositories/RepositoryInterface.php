<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * Busca un registro por ID filtrando por el usuario propietario.
     * @param int $id ID del registro.
     * @param int $userId ID del usuario propietario.
     * @return Model|null
     */
    public function findById(int $id, int $userId): ?Model;

    /**
     * Retorna todos los registros del usuario.
     * @param int $userId ID del usuario propietario.
     * @return Collection
     */
    public function listByUser(int $userId): Collection;

    /**
     * Crea un nuevo registro.
     * @param array $data Datos del nuevo registro.
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * Actualiza un registro existente y lo retorna.
     * @param Model $model Instancia del registro a actualizar.
     * @param array $data Datos a actualizar.
     * @return Model
     */
    public function update(Model $model, array $data): Model;
}
