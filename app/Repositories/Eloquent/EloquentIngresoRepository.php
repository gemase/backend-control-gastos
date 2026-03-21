<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\IngresoRepositoryInterface;
use App\Models\Ingreso;
use Illuminate\Database\Eloquent\Collection;

class EloquentIngresoRepository extends EloquentBaseRepository implements IngresoRepositoryInterface
{
    /**
     * Retorna el modelo Eloquent asociado a este repositorio.
     * @return string
     */
    protected function model(): string
    {
        return Ingreso::class;
    }

    /**
     * Suma el monto total de los ingresos de un usuario en un periodo.
     * @param int $userId ID del usuario propietario.
     * @param int $periodoId ID del periodo.
     * @return float
     */
    public function sumarMontoPorPeriodo(int $userId, int $periodoId): float
    {
        return (float) Ingreso::where('creado_por', $userId)
            ->where('id_periodo', $periodoId)
            ->sum('monto');
    }

    /**
     * Retorna los ingresos activos de un usuario en un periodo.
     * @param int $userId ID del usuario propietario.
     * @param int $periodoId ID del periodo.
     * @return Collection
     */
    public function listarActivosPorPeriodo(int $userId, int $periodoId): Collection
    {
        return Ingreso::where('creado_por', $userId)
            ->where('id_periodo', $periodoId)
            ->where('estatus', Ingreso::ESTATUS_ACTIVO)
            ->get();
    }
}
