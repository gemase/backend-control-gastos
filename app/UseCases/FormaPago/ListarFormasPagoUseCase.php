<?php

namespace App\UseCases\FormaPago;

use App\Contracts\Repositories\FormaPagoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ListarFormasPagoUseCase
{
    public function __construct(
        private readonly FormaPagoRepositoryInterface $repository
    ) {}

    public function execute(int $userId): Collection
    {
        return $this->repository->listByUser($userId);
    }
}
