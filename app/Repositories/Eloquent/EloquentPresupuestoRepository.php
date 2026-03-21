<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\PresupuestoRepositoryInterface;
use App\Models\Presupuesto;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class EloquentPresupuestoRepository extends EloquentBaseRepository implements PresupuestoRepositoryInterface
{
    private const RELATIONS = ['periodo', 'categoria'];

    /**
     * Retorna el modelo Eloquent asociado a este repositorio.
     * @return string
     */
    protected function model(): string
    {
        return Presupuesto::class;
    }

    /**
     * Busca un presupuesto por ID con sus relaciones, filtrando por el usuario propietario.
     * @param int $id ID del presupuesto.
     * @param int $userId ID del usuario propietario.
     * @return Model|null
     */
    public function findById(int $id, int $userId): ?Model
    {
        return Presupuesto::with(self::RELATIONS)
            ->where('id', $id)
            ->where('creado_por', $userId)
            ->first();
    }

    /**
     * Retorna todos los presupuestos del usuario con sus relaciones.
     * @param int $userId ID del usuario propietario.
     * @return Collection
     */
    public function listByUser(int $userId): Collection
    {
        return Presupuesto::with(self::RELATIONS)
            ->where('creado_por', $userId)
            ->get();
    }

    /**
     * Actualiza un presupuesto existente, recarga sus relaciones y lo retorna.
     * @param Model $model Instancia del presupuesto a actualizar.
     * @param array $data Datos a actualizar.
     * @return Model
     */
    public function update(Model $model, array $data): Model
    {
        $model->update($data);
        $model->load(self::RELATIONS);

        return $model;
    }

    /**
     * Inserta múltiples presupuestos de forma masiva.
     * @param array $data Arreglo de registros a insertar.
     * @return void
     */
    public function insertarMultiple(array $data): void
    {
        Presupuesto::insert($data);
    }

    /**
     * Retorna los presupuestos de un usuario en un periodo que tengan categoría asignada.
     * @param int $userId ID del usuario propietario.
     * @param int $periodoId ID del periodo.
     * @return Collection
     */
    public function listarConCategoriaPorPeriodo(int $userId, int $periodoId): Collection
    {
        return Presupuesto::with('categoria')
            ->where('creado_por', $userId)
            ->where('id_periodo', $periodoId)
            ->whereNotNull('id_categoria')
            ->get();
    }
}
