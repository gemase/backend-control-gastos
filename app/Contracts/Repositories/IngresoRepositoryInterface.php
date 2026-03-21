<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface IngresoRepositoryInterface extends RepositoryInterface
{
    /**
     * Suma el monto total de los ingresos de un usuario en un periodo.
     * @param int $userId ID del usuario propietario.
     * @param int $periodoId ID del periodo.
     * @return float
     */
    public function sumarMontoPorPeriodo(int $userId, int $periodoId): float;

    /**
     * Retorna los ingresos activos de un usuario en un periodo.
     * @param int $userId ID del usuario propietario.
     * @param int $periodoId ID del periodo.
     * @return Collection
     */
    public function listarActivosPorPeriodo(int $userId, int $periodoId): Collection;
}
