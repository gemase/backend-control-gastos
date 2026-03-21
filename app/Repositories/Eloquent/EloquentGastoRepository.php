<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\GastoRepositoryInterface;
use App\Models\Gasto;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class EloquentGastoRepository extends EloquentBaseRepository implements GastoRepositoryInterface
{
    private const RELATIONS = ['formaPago', 'categoria', 'periodo'];

    /**
     * Retorna el modelo Eloquent asociado a este repositorio.
     * @return string
     */
    protected function model(): string
    {
        return Gasto::class;
    }

    /**
     * Busca un gasto por ID con sus relaciones, filtrando por el usuario propietario.
     * @param int $id ID del gasto.
     * @param int $userId ID del usuario propietario.
     * @return Model|null
     */
    public function findById(int $id, int $userId): ?Model
    {
        return Gasto::with(self::RELATIONS)
            ->where('id', $id)
            ->where('creado_por', $userId)
            ->first();
    }

    /**
     * Retorna todos los gastos del usuario con sus relaciones.
     * @param int $userId ID del usuario propietario.
     * @return Collection
     */
    public function listByUser(int $userId): Collection
    {
        return Gasto::with(self::RELATIONS)
            ->where('creado_por', $userId)
            ->get();
    }

    /**
     * Crea un nuevo gasto y carga sus relaciones antes de retornarlo.
     * @param array $data Datos del nuevo gasto.
     * @return Model
     */
    public function create(array $data): Model
    {
        $gasto = Gasto::create($data);
        $gasto->load(self::RELATIONS);

        return $gasto;
    }

    /**
     * Actualiza un gasto existente, recarga sus relaciones y lo retorna.
     * @param Model $model Instancia del gasto a actualizar.
     * @param array $data  Datos a actualizar.
     * @return Model
     */
    public function update(Model $model, array $data): Model
    {
        $model->update($data);
        $model->load(self::RELATIONS);

        return $model;
    }

    /**
     * Suma el monto total de los gastos activos de un usuario en un periodo.
     * @param int $userId ID del usuario propietario.
     * @param int $periodoId ID del periodo.
     * @return float
     */
    public function sumarMontoActivoPorPeriodo(int $userId, int $periodoId): float
    {
        return (float) Gasto::where('creado_por', $userId)
            ->where('id_periodo', $periodoId)
            ->where('estatus', Gasto::ESTATUS_ACTIVO)
            ->sum('monto');
    }

    /**
     * Retorna los gastos activos de un usuario en un periodo con sus relaciones.
     * @param int $userId ID del usuario propietario.
     * @param int $periodoId ID del periodo.
     * @return Collection
     */
    public function listarActivosPorPeriodo(int $userId, int $periodoId): Collection
    {
        return Gasto::with('categoria', 'formaPago')
            ->where('creado_por', $userId)
            ->where('id_periodo', $periodoId)
            ->where('estatus', Gasto::ESTATUS_ACTIVO)
            ->get();
    }
}
