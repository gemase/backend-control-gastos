<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class EloquentBaseRepository
{
    abstract protected function model(): string;

    /**
     * Busca un registro por ID filtrando por el usuario propietario.
     * @param int $id ID del registro.
     * @param int $userId ID del usuario propietario.
     * @return Model|null
     */
    public function findById(int $id, int $userId): ?Model
    {
        return ($this->model())::where('id', $id)
            ->where('creado_por', $userId)
            ->first();
    }

    /**
     * Retorna todos los registros pertenecientes al usuario.
     * @param int $userId ID del usuario propietario.
     * @return Collection
     */
    public function listByUser(int $userId): Collection
    {
        return ($this->model())::where('creado_por', $userId)->get();
    }

    /**
     * Crea un nuevo registro con los datos proporcionados.
     * @param array $data Datos del nuevo registro.
     * @return Model
     */
    public function create(array $data): Model
    {
        return ($this->model())::create($data);
    }

    /**
     * Actualiza un registro existente y retorna su versión más reciente.
     * @param Model $model Instancia del modelo a actualizar.
     * @param array $data  Datos a actualizar.
     * @return Model
     */
    public function update(Model $model, array $data): Model
    {
        $model->update($data);

        return $model->fresh();
    }
}
