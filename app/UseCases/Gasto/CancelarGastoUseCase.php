<?php

namespace App\UseCases\Gasto;

use App\Contracts\Repositories\GastoRepositoryInterface;
use App\Exceptions\BusinessException;
use App\Exceptions\NotFoundException;
use App\Models\Gasto;

class CancelarGastoUseCase
{
    public function __construct(
        private readonly GastoRepositoryInterface $repository
    ) {}

    public function execute(int $id, int $idUsuario, array $datos): Gasto
    {
        $gasto = $this->repository->findById($id, $idUsuario);

        if (!$gasto) {
            throw new NotFoundException('El gasto no fue encontrado.');
        }

        if ($gasto->estatus == Gasto::ESTATUS_CANCELADO) {
            throw new BusinessException('El gasto ya se encuentra en estatus (' . Gasto::ESTATUS_DESCRIPCIONES[Gasto::ESTATUS_CANCELADO] . ').');
        }

        return $this->repository->update($gasto, $datos);
    }
}
