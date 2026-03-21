<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\CategoriaRepositoryInterface;
use App\Models\Categoria;

class EloquentCategoriaRepository extends EloquentBaseRepository implements CategoriaRepositoryInterface
{
    /**
     * Retorna el modelo Eloquent asociado a este repositorio.
     * @return string
     */
    protected function model(): string
    {
        return Categoria::class;
    }

    /**
     * Verifica si ya existe una categoría con el mismo nombre para el usuario.
     * Permite excluir un ID para no colisionar consigo mismo en actualizaciones.
     * @param int $userId ID del usuario propietario.
     * @param string $name Nombre a verificar.
     * @param int|null $excludeId ID a excluir de la búsqueda (opcional).
     * @return bool
     */
    public function existePorNombre(int $userId, string $name, ?int $excludeId = null): bool
    {
        return Categoria::where('creado_por', $userId)
            ->where('nombre', $name)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }
}
