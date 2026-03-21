<?php

namespace App\UseCases\Gasto;

use App\Contracts\Repositories\GastoRepositoryInterface;
use App\Contracts\Repositories\PeriodoRepositoryInterface;
use App\Exceptions\BusinessException;
use App\Exceptions\NotFoundException;
use App\Models\Gasto;

class ActualizarGastoUseCase
{
    public function __construct(
        private readonly GastoRepositoryInterface $repository,
        private readonly PeriodoRepositoryInterface $periodoRepository
    ) {}

    public function execute(int $id, int $idUsuario, array $datos): Gasto
    {
        $gasto = $this->repository->findById($id, $idUsuario);

        if (!$gasto) {
            throw new NotFoundException('El gasto no fue encontrado.');
        }

        if ($gasto->estatus == Gasto::ESTATUS_CANCELADO) {
            throw new BusinessException('El gasto no puede ser actualizado porque se encuentra (' . Gasto::ESTATUS_DESCRIPCIONES[Gasto::ESTATUS_CANCELADO] . ').');
        }

        if ((string) $gasto->fecha !== (string) $datos['fecha']) {
            $periodo = $this->periodoRepository->buscarPorFecha($idUsuario, $datos['fecha']);

            $datos['id_periodo'] = $periodo?->id;
            $datos['estatus'] = $periodo ? Gasto::ESTATUS_ACTIVO : Gasto::ESTATUS_SIN_PERIODO;
        }

        return $this->repository->update($gasto, $datos);
    }
}
