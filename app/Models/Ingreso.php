<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Periodo;

class Ingreso extends Model
{
    /**
     * Indica que el ingreso de movimiento no tiene un periodo asociado.
     */
    public const ESTATUS_SIN_PERIODO = 2;

    /**
     * Indica que el ingreso de movimiento está activo.
     */
    public const ESTATUS_ACTIVO = 1;

    /**
     * Indica que el ingreso de movimiento está inactivo.
     */
    public const ESTATUS_INACTIVO = 0;

    /**
     * Nombre de la tabla asociada al modelo.
     * @var string
     */
    protected $table = 'ingresos';

    /**
     * Atributos asignables masivamente.
     * @var array<int, string>
     */
    protected $fillable = [
        'creado_por',
        'nombre',
        'fecha',
        'monto',
        'estatus',
        'id_periodo',
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
        return $estatus == self::ESTATUS_ACTIVO ? 'Activo' : ($estatus == self::ESTATUS_INACTIVO ? 'Inactivo' : 'Sin periodo');
    }

    /**
     * Define la relación con el modelo User.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    /**
     * Define la relación con el modelo Periodo.
     */
    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'id_periodo');
    }
}
