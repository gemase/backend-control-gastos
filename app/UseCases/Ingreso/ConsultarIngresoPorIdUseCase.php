<?php

namespace App\UseCases\Ingreso;

use App\Contracts\Repositories\IngresoRepositoryInterface;
use App\Exceptions\NotFoundException;
use App\Models\Ingreso;

class ConsultarIngresoPorIdUseCase
{
    public function __construct(
        private readonly IngresoRepositoryInterface $repository
    ) {}

    public function execute(int $id, int $idUsuario): Ingreso
    {
        $ingreso = $this->repository->findById($id, $idUsuario);

        if (!$ingreso) {
            throw new NotFoundException('El ingreso no fue encontrado.');
        }

        return $ingreso;
    }
}
