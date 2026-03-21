<?php

namespace App\UseCases\FormaPago;

use App\Contracts\Repositories\FormaPagoRepositoryInterface;
use App\Exceptions\BusinessException;
use App\Models\FormaPago;

class CrearFormaPagoUseCase
{
    public function __construct(
        private readonly FormaPagoRepositoryInterface $repository
    ) {}

    public function execute(int $userId, array $data): FormaPago
    {
        if ($this->repository->existePorNombre($userId, $data['nombre'])) {
            throw new BusinessException('El nombre de forma de pago ya existe.');
        }

        return $this->repository->create([...$data, 'creado_por' => $userId]);
    }
}
