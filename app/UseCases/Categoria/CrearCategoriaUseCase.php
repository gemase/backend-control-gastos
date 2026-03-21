<?php

namespace App\UseCases\Categoria;

use App\Contracts\Repositories\CategoriaRepositoryInterface;
use App\Exceptions\BusinessException;
use App\Models\Categoria;
use App\Models\Periodo;
use App\Models\Presupuesto;

class CrearCategoriaUseCase
{
    public function __construct(
        private readonly CategoriaRepositoryInterface $repository
    ) {}

    public function execute(int $userId, array $data): Categoria
    {
        if ($this->repository->existePorNombre($userId, $data['nombre'])) {
            throw new BusinessException('El nombre de categoría ya existe.');
        }

        $categoria = $this->repository->create([...$data, 'creado_por' => $userId]);

        $periodos = Periodo::where('creado_por', $userId)->get();
        $presupuestos = [];
        foreach ($periodos as $periodo) {
            $presupuestos[] = [
                'creado_por' => $categoria->creado_por,
                'id_periodo' => $periodo->id,
                'id_categoria' => $categoria->id,
                'monto' => $categoria->presupuesto_base ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($presupuestos)) {
            Presupuesto::insert($presupuestos);
        }

        return $categoria;
    }
}
