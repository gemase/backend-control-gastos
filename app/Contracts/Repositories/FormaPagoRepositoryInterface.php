<?php

namespace App\Contracts\Repositories;

interface FormaPagoRepositoryInterface extends RepositoryInterface
{
    /**
     * Verifica si ya existe una forma de pago con el mismo nombre para el usuario.
     * Permite excluir un ID para no colisionar consigo mismo en actualizaciones.
     * @param int $userId ID del usuario propietario.
     * @param string $name Nombre a verificar.
     * @param int|null $excludeId ID a excluir de la búsqueda (opcional).
     * @return bool
     */
    public function existePorNombre(int $userId, string $name, ?int $excludeId = null): bool;

    /**
     * Inserta múltiples registros de forma masiva.
     * @param array $data Arreglo de registros a insertar.
     * @return void
     */
    public function insertarMultiple(array $data): void;
}
