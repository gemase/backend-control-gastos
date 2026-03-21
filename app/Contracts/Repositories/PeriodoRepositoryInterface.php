<?php

namespace App\Contracts\Repositories;

use App\Models\Periodo;

interface PeriodoRepositoryInterface extends RepositoryInterface
{
    /**
     * Busca el periodo que contenga la fecha indicada para el usuario.
     * @param int $userId ID del usuario propietario.
     * @param string $date Fecha a buscar (formato Y-m-d).
     * @return Periodo|null
     */
    public function buscarPorFecha(int $userId, string $date): ?Periodo;

    /**
     * Verifica si existe un periodo que se solape con el rango de fechas dado.
     * Permite excluir un ID para no colisionar consigo mismo en actualizaciones.
     * @param int $userId ID del usuario propietario.
     * @param string $fechaInicio Fecha de inicio del nuevo rango (Y-m-d).
     * @param string $fechaFin Fecha de fin del nuevo rango (Y-m-d).
     * @param int|null $excludeId ID a excluir de la búsqueda (opcional).
     * @return Periodo|null
     */
    public function existeEmpalmeFechas(int $userId, string $fechaInicio, string $fechaFin, ?int $excludeId = null): ?Periodo;
}
