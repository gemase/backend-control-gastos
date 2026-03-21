<?php

namespace App\UseCases\FormaPago;

use App\Contracts\Repositories\FormaPagoRepositoryInterface;
use App\Exceptions\NotFoundException;
use App\Models\FormaPago;

class ConsultarFormaPagoPorIdUseCase
{
    public function __construct(
        private readonly FormaPagoRepositoryInterface $repository
    ) {}

    public function execute(int $id, int $userId): FormaPago
    {
        $formaPago = $this->repository->findById($id, $userId);

        if (!$formaPago) {
            throw new NotFoundException('La forma de pago no fue encontrada.');
        }

        return $formaPago;
    }
}
