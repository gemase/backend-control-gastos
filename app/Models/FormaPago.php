<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    /**
     * Indica que la forma de pago está activa.
     */
    public const ESTATUS_ACTIVO = 1;

    /**
     * Indica que la forma de pago está inactiva.
     */
    public const ESTATUS_INACTIVO = 0;

    /**
     * Formas de pago predeterminadas para nuevos usuarios.
     * @var array<int, array<string, string|null>>
     */
    public const FORMAS_PAGO_PREDETERMINADAS = [
        [
            'nombre' => 'Efectivo',
            'descripcion' => null,
        ],
        [
            'nombre' => 'Tarjeta de crédito',
            'descripcion' => null,
        ],
        [
            'nombre' => 'Tarjeta de débito',
            'descripcion' => null,
        ],
    ];

    /**
     * Nombre de la tabla asociada al modelo.
     * @var string
     */
    protected $table = 'formas_pago';

    /**
     * Atributos asignables masivamente.
     * @var array<int, string>
     */
    protected $fillable = [
        'creado_por',
        'nombre',
        'descripcion',
        'estatus',
    ];

    /**
     * Atributos adicionales que se incluyen en las representaciones del modelo.
     * @var array<int, string>
     */
    protected $appends = ['estatus_descripcion'];

    /**
     * Devuelve la descripción del estatus.
     */
    public function getEstatusDescripcionAttribute()
    {
        $estatus = $this->estatus ?? self::ESTATUS_ACTIVO;
        return $estatus == self::ESTATUS_ACTIVO ? 'Activo' : 'Inactivo';
    }

    /**
     * Define la relación con el modelo User.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }
}
