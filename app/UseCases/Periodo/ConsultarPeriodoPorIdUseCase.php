<?php

namespace App\UseCases\Periodo;

use App\Contracts\Repositories\GastoRepositoryInterface;
use App\Contracts\Repositories\IngresoRepositoryInterface;
use App\Contracts\Repositories\PeriodoRepositoryInterface;
use App\Exceptions\NotFoundException;

class ConsultarPeriodoPorIdUseCase
{
    public function __construct(
        private readonly PeriodoRepositoryInterface $repository,
        private readonly IngresoRepositoryInterface $ingresoRepository,
        private readonly GastoRepositoryInterface $gastoRepository
    ) {}

    public function execute(int $id, int $idUsuario): array
    {
        $periodo = $this->repository->findById($id, $idUsuario);

        if (!$periodo) {
            throw new NotFoundException('El periodo no fue encontrado.');
        }

        $ingresos = $this->ingresoRepository->sumarMontoPorPeriodo($idUsuario, $id);
        $gastos = $this->gastoRepository->sumarMontoActivoPorPeriodo($idUsuario, $id);

        return array_merge($periodo->toArray(), [
            'ingresos' => $ingresos,
            'gastos' => $gastos,
            'balance' => $ingresos - $gastos,
        ]);
    }
}
