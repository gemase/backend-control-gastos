<?php

namespace App\UseCases\Presupuesto;

use App\Contracts\Repositories\PresupuestoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ListarPresupuestosUseCase
{
    public function __construct(
        private readonly PresupuestoRepositoryInterface $repository
    ) {}

    public function execute(int $idUsuario): Collection
    {
        return $this->repository->listByUser($idUsuario);
    }
}
