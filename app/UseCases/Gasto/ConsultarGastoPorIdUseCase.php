<?php

namespace App\UseCases\Gasto;

use App\Contracts\Repositories\GastoRepositoryInterface;
use App\Exceptions\NotFoundException;
use App\Models\Gasto;

class ConsultarGastoPorIdUseCase
{
    public function __construct(
        private readonly GastoRepositoryInterface $repository
    ) {}

    public function execute(int $id, int $idUsuario): Gasto
    {
        $gasto = $this->repository->findById($id, $idUsuario);

        if (!$gasto) {
            throw new NotFoundException('El gasto no fue encontrado.');
        }

        return $gasto;
    }
}
