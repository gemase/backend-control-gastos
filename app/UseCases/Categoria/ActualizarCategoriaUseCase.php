<?php

namespace App\UseCases\Categoria;

use App\Contracts\Repositories\CategoriaRepositoryInterface;
use App\Exceptions\BusinessException;
use App\Exceptions\NotFoundException;
use App\Models\Categoria;

class ActualizarCategoriaUseCase
{
    public function __construct(
        private readonly CategoriaRepositoryInterface $repository
    ) {}

    public function execute(int $id, int $userId, array $data): Categoria
    {
        $categoria = $this->repository->findById($id, $userId);

        if (!$categoria) {
            throw new NotFoundException('La categoría no fue encontrada.');
        }

        if ($this->repository->existePorNombre($userId, $data['nombre'], $id)) {
            throw new BusinessException('El nombre de categoría ya existe.');
        }

        return $this->repository->update($categoria, $data);
    }
}
