<?php

namespace App\UseCases\Periodo;

use App\Contracts\Repositories\GastoRepositoryInterface;
use App\Contracts\Repositories\IngresoRepositoryInterface;
use App\Contracts\Repositories\PeriodoRepositoryInterface;
use Illuminate\Support\Collection;

class ListarPeriodosUseCase
{
    public function __construct(
        private readonly PeriodoRepositoryInterface $repository,
        private readonly IngresoRepositoryInterface $ingresoRepository,
        private readonly GastoRepositoryInterface $gastoRepository
    ) {}

    public function execute(int $idUsuario): Collection
    {
        return $this->repository->listByUser($idUsuario)
            ->map(function ($periodo) use ($idUsuario) {
                $ingresos = $this->ingresoRepository->sumarMontoPorPeriodo($idUsuario, $periodo->id);
                $gastos = $this->gastoRepository->sumarMontoActivoPorPeriodo($idUsuario, $periodo->id);

                return array_merge($periodo->toArray(), [
                    'ingresos' => $ingresos,
                    'gastos' => $gastos,
                    'balance' => $ingresos - $gastos,
                ]);
            });
    }
}
