<?php

namespace App\Services\Usuario;

use App\Models\FormaPago;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CrearUsuarioService
{
    /**
     * Crea un usuario y registra sus formas de pago por defecto en una misma transacción.
     * @param array<string, mixed> $datosValidados
     */
    public function ejecutar(array $datosValidados): User
    {
        return DB::transaction(function () use ($datosValidados) {
            $usuario = User::create([
                'name' => $datosValidados['name'],
                'email' => $datosValidados['email'],
                'password' => bcrypt($datosValidados['password']),
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

            FormaPago::insert($formasPago);

            return $usuario;
        });
    }
}
