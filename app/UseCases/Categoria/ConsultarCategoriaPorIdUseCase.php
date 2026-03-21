<?php

namespace App\UseCases\Categoria;

use App\Contracts\Repositories\CategoriaRepositoryInterface;
use App\Exceptions\NotFoundException;
use App\Models\Categoria;

class ConsultarCategoriaPorIdUseCase
{
    public function __construct(
        private readonly CategoriaRepositoryInterface $repository
    ) {}

    public function execute(int $id, int $userId): Categoria
    {
        $categoria = $this->repository->findById($id, $userId);

        if (!$categoria) {
            throw new NotFoundException('La categoría no fue encontrada.');
        }

        return $categoria;
    }
}
