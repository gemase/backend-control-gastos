<?php

namespace App\UseCases\Ingreso;

use App\Contracts\Repositories\IngresoRepositoryInterface;
use App\Contracts\Repositories\PeriodoRepositoryInterface;
use App\Exceptions\BusinessException;
use App\Exceptions\NotFoundException;
use App\Models\Ingreso;

class ActualizarIngresoUseCase
{
    public function __construct(
        private readonly IngresoRepositoryInterface $repository,
        private readonly PeriodoRepositoryInterface $periodoRepository
    ) {}

    public function execute(int $id, int $idUsuario, array $datos): Ingreso
    {
        $ingreso = $this->repository->findById($id, $idUsuario);

        if (!$ingreso) {
            throw new NotFoundException('El ingreso no fue encontrado.');
        }

        if ($ingreso->estatus == Ingreso::ESTATUS_INACTIVO) {
            throw new BusinessException('No se puede editar un ingreso inactivo.');
        }

        if ((string) $ingreso->fecha !== (string) $datos['fecha']) {
            $periodo = $this->periodoRepository->buscarPorFecha($idUsuario, $datos['fecha']);

            $datos['id_periodo'] = $periodo?->id;
            $datos['estatus'] = $periodo ? Ingreso::ESTATUS_ACTIVO : Ingreso::ESTATUS_SIN_PERIODO;
        }

        return $this->repository->update($ingreso, $datos);
    }
}
