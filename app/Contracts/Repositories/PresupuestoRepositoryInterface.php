<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface PresupuestoRepositoryInterface extends RepositoryInterface
{
    /**
     * Inserta múltiples presupuestos de forma masiva.
     * @param array $data Arreglo de registros a insertar.
     * @return void
     */
    public function insertarMultiple(array $data): void;

    /**
     * Retorna los presupuestos de un usuario en un periodo que tengan categoría asignada.
     * @param int $userId ID del usuario propietario.
     * @param int $periodoId ID del periodo.
     * @return Collection
     */
    public function listarConCategoriaPorPeriodo(int $userId, int $periodoId): Collection;
}
