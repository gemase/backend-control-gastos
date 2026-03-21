<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\PeriodoRepositoryInterface;
use App\Models\Periodo;
use Illuminate\Database\Eloquent\Collection;

class EloquentPeriodoRepository extends EloquentBaseRepository implements PeriodoRepositoryInterface
{
    /**
     * Retorna el modelo Eloquent asociado a este repositorio.
     * @return string
     */
    protected function model(): string
    {
        return Periodo::class;
    }

    /**
     * Retorna todos los periodos del usuario ordenados por fecha de inicio descendente.
     * @param int $userId ID del usuario propietario.
     * @return Collection
     */
    public function listByUser(int $userId): Collection
    {
        return Periodo::where('creado_por', $userId)
            ->orderBy('fecha_inicio', 'desc')
            ->get();
    }

    /**
     * Busca el periodo que contenga la fecha indicada para el usuario.
     * @param int $userId ID del usuario propietario.
     * @param string $date Fecha a buscar (formato Y-m-d).
     * @return Periodo|null
     */
    public function buscarPorFecha(int $userId, string $date): ?Periodo
    {
        return Periodo::where('creado_por', $userId)
            ->where('fecha_inicio', '<=', $date)
            ->where('fecha_fin', '>=', $date)
            ->first();
    }

    /**
     * Verifica si existe un periodo que se solape con el rango de fechas dado.
     * Permite excluir un ID para no colisionar consigo mismo en actualizaciones.
     * @param int $userId ID del usuario propietario.
     * @param string $fechaInicio Fecha de inicio del nuevo rango (Y-m-d).
     * @param string $fechaFin Fecha de fin del nuevo rango (Y-m-d).
     * @param int|null $excludeId ID a excluir de la búsqueda (opcional).
     * @return Periodo|null
     */
    public function existeEmpalmeFechas(int $userId, string $fechaInicio, string $fechaFin, ?int $excludeId = null): ?Periodo
    {
        return Periodo::where('creado_por', $userId)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where('fecha_inicio', '<=', $fechaFin)
            ->where('fecha_fin', '>=', $fechaInicio)
            ->first();
    }
}
