<?php

namespace App\UseCases\Categoria;

use App\Contracts\Repositories\CategoriaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ListarCategoriasUseCase
{
    public function __construct(
        private readonly CategoriaRepositoryInterface $repository
    ) {}

    public function execute(int $userId): Collection
    {
        return $this->repository->listByUser($userId);
    }
}
