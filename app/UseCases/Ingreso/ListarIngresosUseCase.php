<?php

namespace App\UseCases\Ingreso;

use App\Contracts\Repositories\IngresoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ListarIngresosUseCase
{
    public function __construct(
        private readonly IngresoRepositoryInterface $repository
    ) {}

    public function execute(int $idUsuario): Collection
    {
        return $this->repository->listByUser($idUsuario);
    }
}
