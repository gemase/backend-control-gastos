<?php

namespace App\UseCases\Ingreso;

use App\Contracts\Repositories\IngresoRepositoryInterface;
use App\Contracts\Repositories\PeriodoRepositoryInterface;
use App\Models\Ingreso;

class CrearIngresoUseCase
{
    public function __construct(
        private readonly IngresoRepositoryInterface $repository,
        private readonly PeriodoRepositoryInterface $periodoRepository
    ) {}

    public function execute(int $idUsuario, array $datos): Ingreso
    {
        $periodo = $this->periodoRepository->buscarPorFecha($idUsuario, $datos['fecha']);

        $datos['creado_por'] = $idUsuario;
        $datos['id_periodo'] = $periodo?->id;
        $datos['estatus'] = $periodo ? Ingreso::ESTATUS_ACTIVO : Ingreso::ESTATUS_SIN_PERIODO;

        return $this->repository->create($datos);
    }
}
