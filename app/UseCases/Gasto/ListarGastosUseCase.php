<?php

namespace App\UseCases\Gasto;

use App\Contracts\Repositories\GastoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ListarGastosUseCase
{
    public function __construct(
        private readonly GastoRepositoryInterface $repository
    ) {}

    public function execute(int $idUsuario): Collection
    {
        return $this->repository->listByUser($idUsuario);
    }
}
