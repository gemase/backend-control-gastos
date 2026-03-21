<?php

namespace App\UseCases\Periodo;

use App\Contracts\Repositories\GastoRepositoryInterface;
use App\Contracts\Repositories\IngresoRepositoryInterface;
use App\Contracts\Repositories\PeriodoRepositoryInterface;
use App\Contracts\Repositories\PresupuestoRepositoryInterface;
use App\Exceptions\NotFoundException;

class DashboardPeriodoUseCase
{
    public function __construct(
        private readonly PeriodoRepositoryInterface $repository,
        private readonly IngresoRepositoryInterface $ingresoRepository,
        private readonly GastoRepositoryInterface $gastoRepository,
        private readonly PresupuestoRepositoryInterface $presupuestoRepository
    ) {}

    public function execute(int $id, int $idUsuario): array
    {
        $periodo = $this->repository->findById($id, $idUsuario);

        if (!$periodo) {
            throw new NotFoundException('El periodo no fue encontrado.');
        }

        $totalIngresos = $this->ingresoRepository->sumarMontoPorPeriodo($idUsuario, $id);
        $totalGastos = $this->gastoRepository->sumarMontoActivoPorPeriodo($idUsuario, $id);

        $presupuestos = $this->presupuestoRepository->listarConCategoriaPorPeriodo($idUsuario, $id)
            ->map(function ($presupuesto) {
                $diferencia = (float) $presupuesto->monto > 0 ? (float) $presupuesto->monto - $presupuesto->gastado : null;
                $porcentaje = (float) $presupuesto->monto > 0 ? round(($presupuesto->gastado / (float) $presupuesto->monto) * 100, 1) : null;

                return [
                    'id' => $presupuesto->id,
                    'categoria' => $presupuesto->categoria?->nombre,
                    'presupuesto' => (float) $presupuesto->monto,
                    'gastado' => $presupuesto->gastado,
                    'diferencia' => $diferencia,
                    'porcentaje' => $porcentaje,
                ];
            });

        $totalPresupuestado     = $presupuestos->sum('presupuesto');
        $totalGastadoCategorias = $presupuestos->sum('gastado');

        $resumen = [
            'ingresos' => round($totalIngresos, 2),
            'gastos' => round($totalGastos, 2),
            'balance' => round($totalIngresos - $totalGastos, 2),
            'sin_asignar' => round($totalIngresos - $totalPresupuestado, 2),
        ];

        $totales = [
            'id' => null,
            'categoria' => 'Total',
            'presupuesto' => $totalPresupuestado,
            'gastado' => $totalGastadoCategorias,
            'diferencia' => $totalIngresos - $totalGastadoCategorias,
            'porcentaje' => $totalPresupuestado > 0 ? round(($totalGastadoCategorias / $totalPresupuestado) * 100, 1) : null,
        ];

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

        $ultimosMovimientos = $gastos->concat($ingresos)->sortByDesc('fecha')->take(5)->values();

        return [
            'resumen' => $resumen,
            'presupuestos' => $presupuestos,
            'totales' => $totales,
            'ultimos_movimientos' => $ultimosMovimientos,
        ];
    }
}
