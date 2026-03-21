<?php

namespace App\UseCases\Periodo;

use App\Contracts\Repositories\GastoRepositoryInterface;
use App\Contracts\Repositories\IngresoRepositoryInterface;
use App\Contracts\Repositories\PeriodoRepositoryInterface;
use App\Exceptions\NotFoundException;
use Illuminate\Support\Collection;

class MovimientosPeriodoUseCase
{
    public function __construct(
        private readonly PeriodoRepositoryInterface $repository,
        private readonly IngresoRepositoryInterface $ingresoRepository,
        private readonly GastoRepositoryInterface $gastoRepository
    ) {}

    public function execute(int $id, int $idUsuario): Collection
    {
        $periodo = $this->repository->findById($id, $idUsuario);

        if (!$periodo) {
            throw new NotFoundException('El periodo no fue encontrado.');
        }

        $gastos = $this->gastoRepository->listarActivosPorPeriodo($idUsuario, $id)
            ->map(fn($gasto) => [
                'nombre' => $gasto->descripcion,
                'fecha' => $gasto->fecha,
                'categoria' => $gasto->categoria?->nombre,
                'forma_pago' => $gasto->formaPago?->nombre,
                'tipo' => 'Gasto',
                'monto' => -(float) $gasto->monto,
            ]);

        $ingresos = $this->ingresoRepository->listarActivosPorPeriodo($idUsuario, $id)
            ->map(fn($ingreso) => [
                'nombre' => $ingreso->nombre,
                'fecha' => $ingreso->fecha,
                'categoria' => null,
                'forma_pago' => null,
                'tipo' => 'Ingreso',
                'monto' => (float) $ingreso->monto,
            ]);

        return $gastos->concat($ingresos)->sortByDesc('fecha')->values();
    }
}
