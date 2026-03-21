<?php

namespace App\UseCases\Periodo;

use App\Contracts\Repositories\CategoriaRepositoryInterface;
use App\Contracts\Repositories\PeriodoRepositoryInterface;
use App\Contracts\Repositories\PresupuestoRepositoryInterface;
use App\Exceptions\BusinessException;
use App\Models\Periodo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class CrearPeriodoUseCase
{
    public function __construct(
        private readonly PeriodoRepositoryInterface $repository,
        private readonly CategoriaRepositoryInterface $categoriaRepository,
        private readonly PresupuestoRepositoryInterface $presupuestoRepository
    ) {}

    public function execute(int $idUsuario, array $datos): Periodo
    {
        $datos['creado_por'] = $idUsuario;
        $datos['mes'] = Str::ucfirst(Carbon::parse($datos['fecha_fin'])->locale('es')->translatedFormat('F'));

        $periodoEmpalmado = $this->repository->existeEmpalmeFechas($idUsuario, $datos['fecha_inicio'], $datos['fecha_fin']);

        if ($periodoEmpalmado) {
            throw new BusinessException('Las fechas del periodo se empalman con un periodo existente del ' . $periodoEmpalmado->fecha_inicio . ' al ' . $periodoEmpalmado->fecha_fin . '.');
        }

        $periodo = $this->repository->create($datos);

        $categorias   = $this->categoriaRepository->listByUser($idUsuario);
        $presupuestos = [
            [
                'creado_por' => $periodo->creado_por,
                'id_periodo' => $periodo->id,
                'id_categoria' => null,
                'monto' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($categorias as $categoria) {
            $presupuestos[] = [
                'creado_por' => $periodo->creado_por,
                'id_periodo' => $periodo->id,
                'id_categoria' => $categoria->id,
                'monto' => $categoria->presupuesto_base ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $this->presupuestoRepository->insertarMultiple($presupuestos);

        return $periodo;
    }
}
