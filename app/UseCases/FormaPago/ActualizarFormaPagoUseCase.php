<?php

namespace App\UseCases\FormaPago;

use App\Contracts\Repositories\FormaPagoRepositoryInterface;
use App\Exceptions\BusinessException;
use App\Exceptions\NotFoundException;
use App\Models\FormaPago;

class ActualizarFormaPagoUseCase
{
    public function __construct(
        private readonly FormaPagoRepositoryInterface $repository
    ) {}

    public function execute(int $id, int $userId, array $data): FormaPago
    {
        $formaPago = $this->repository->findById($id, $userId);

        if (!$formaPago) {
            throw new NotFoundException('La forma de pago no fue encontrada.');
        }

        if ($this->repository->existePorNombre($userId, $data['nombre'], $id)) {
            throw new BusinessException('El nombre de forma de pago ya existe.');
        }

        return $this->repository->update($formaPago, $data);
    }
}
