<?php

namespace App\UseCases\Usuario;

use App\Contracts\Repositories\FormaPagoRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\FormaPago;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CrearUsuarioUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly FormaPagoRepositoryInterface $formaPagoRepository
    ) {}

    public function execute(array $datos): User
    {
        return DB::transaction(function () use ($datos) {
            $usuario = $this->repository->create([
                'name' => $datos['name'],
                'email' => $datos['email'],
                'password' => bcrypt($datos['password']),
            ]);

            $ahora = now();
            $formasPago = array_map(function (array $formaPago) use ($usuario, $ahora) {
                return [
                    'creado_por' => $usuario->id,
                    'nombre' => $formaPago['nombre'],
                    'descripcion' => $formaPago['descripcion'],
                    'estatus' => FormaPago::ESTATUS_ACTIVO,
                    'created_at' => $ahora,
                    'updated_at' => $ahora,
                ];
            }, FormaPago::FORMAS_PAGO_PREDETERMINADAS);

            $this->formaPagoRepository->insertarMultiple($formasPago);

            return $usuario;
        });
    }
}
