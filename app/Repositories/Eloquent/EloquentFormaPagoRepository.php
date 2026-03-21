<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\FormaPagoRepositoryInterface;
use App\Models\FormaPago;

class EloquentFormaPagoRepository extends EloquentBaseRepository implements FormaPagoRepositoryInterface
{
    /**
     * Retorna el modelo Eloquent asociado a este repositorio.
     * @return string
     */
    protected function model(): string
    {
        return FormaPago::class;
    }

    /**
     * Verifica si ya existe una forma de pago con el mismo nombre para el usuario.
     * Permite excluir un ID para no colisionar consigo mismo en actualizaciones.
     * @param int $userId ID del usuario propietario.
     * @param string $name Nombre a verificar.
     * @param int|null $excludeId ID a excluir de la búsqueda (opcional).
     * @return bool
     */
    public function existePorNombre(int $userId, string $name, ?int $excludeId = null): bool
    {
        return FormaPago::where('creado_por', $userId)
            ->where('nombre', $name)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }

    /**
     * Inserta múltiples registros de forma masiva.
     * @param array $data Arreglo de registros a insertar.
     * @return void
     */
    public function insertarMultiple(array $data): void
    {
        FormaPago::insert($data);
    }
}
