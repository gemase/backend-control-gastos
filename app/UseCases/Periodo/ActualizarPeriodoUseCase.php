<?php

namespace App\UseCases\Periodo;

use App\Contracts\Repositories\PeriodoRepositoryInterface;
use App\Exceptions\BusinessException;
use App\Exceptions\NotFoundException;
use App\Models\Periodo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ActualizarPeriodoUseCase
{
    public function __construct(
        private readonly PeriodoRepositoryInterface $repository
    ) {}

    public function execute(int $id, int $idUsuario, array $datos): Periodo
    {
        $periodo = $this->repository->findById($id, $idUsuario);

        if (!$periodo) {
            throw new NotFoundException('El periodo no fue encontrado.');
        }

        $datos['mes'] = Str::ucfirst(Carbon::parse($datos['fecha_fin'])->locale('es')->translatedFormat('F'));

        $periodoEmpalmado = $this->repository->existeEmpalmeFechas($idUsuario, $datos['fecha_inicio'], $datos['fecha_fin'], $id);

        if ($periodoEmpalmado) {
            throw new BusinessException('Las fechas del periodo se empalman con un periodo existente del ' . $periodoEmpalmado->fecha_inicio . ' al ' . $periodoEmpalmado->fecha_fin . '.');
        }

        return $this->repository->update($periodo, $datos);
    }
}
