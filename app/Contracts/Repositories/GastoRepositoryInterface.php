<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface GastoRepositoryInterface extends RepositoryInterface
{
    /**
     * Suma el monto total de los gastos activos de un usuario en un periodo.
     * @param int $userId ID del usuario propietario.
     * @param int $periodoId ID del periodo.
     * @return float
     */
    public function sumarMontoActivoPorPeriodo(int $userId, int $periodoId): float;

    /**
     * Retorna los gastos activos de un usuario en un periodo con sus relaciones.
     * @param int $userId ID del usuario propietario.
     * @param int $periodoId ID del periodo.
     * @return Collection
     */
    public function listarActivosPorPeriodo(int $userId, int $periodoId): Collection;
}
