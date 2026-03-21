<?php

namespace App\UseCases\Gasto;

use App\Contracts\Repositories\GastoRepositoryInterface;
use App\Contracts\Repositories\PeriodoRepositoryInterface;
use App\Models\Gasto;

class CrearGastoUseCase
{
    public function __construct(
        private readonly GastoRepositoryInterface $repository,
        private readonly PeriodoRepositoryInterface $periodoRepository
    ) {}

    public function execute(int $idUsuario, array $datos): Gasto
    {
        $periodo = $this->periodoRepository->buscarPorFecha($idUsuario, $datos['fecha']);

        $datos['creado_por'] = $idUsuario;
        $datos['id_periodo'] = $periodo?->id;
        $datos['estatus'] = $periodo ? Gasto::ESTATUS_ACTIVO : Gasto::ESTATUS_SIN_PERIODO;

        return $this->repository->create($datos);
    }
}
