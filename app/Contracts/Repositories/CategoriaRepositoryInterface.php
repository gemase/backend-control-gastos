<?php

namespace App\Contracts\Repositories;

interface CategoriaRepositoryInterface extends RepositoryInterface
{
    /**
     * Verifica si ya existe una categoría con el mismo nombre para el usuario.
     * Permite excluir un ID para no colisionar consigo mismo en actualizaciones.
     * @param int $userId ID del usuario propietario.
     * @param string $name Nombre a verificar.
     * @param int|null $excludeId ID a excluir de la búsqueda (opcional).
     * @return bool
     */
    public function existePorNombre(int $userId, string $name, ?int $excludeId = null): bool;
}
