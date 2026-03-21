<?php

namespace App\UseCases\Presupuesto;

use App\Contracts\Repositories\PresupuestoRepositoryInterface;
use App\Exceptions\NotFoundException;
use App\Models\Presupuesto;

class ConsultarPresupuestoPorIdUseCase
{
    public function __construct(
        private readonly PresupuestoRepositoryInterface $repository
    ) {}

    public function execute(int $id, int $idUsuario): Presupuesto
    {
        $presupuesto = $this->repository->findById($id, $idUsuario);

        if (!$presupuesto) {
            throw new NotFoundException('El presupuesto no fue encontrado.');
        }

        return $presupuesto;
    }
}
