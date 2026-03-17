<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    /**
     * Indica que el gasto está sin periodo.
     */
    public const ESTATUS_SIN_PERIODO = 2;

    /**
     * Indica que el gasto está activo.
     */
    public const ESTATUS_ACTIVO = 1;

    /**
     * Indica que el gasto está cancelado.
     */
    public const ESTATUS_CANCELADO = 0;

    /**
     * Descripciones de los estatus.
     */
    public const ESTATUS_DESCRIPCIONES = [
        self::ESTATUS_ACTIVO => 'Activo',
        self::ESTATUS_CANCELADO => 'Cancelado',
        self::ESTATUS_SIN_PERIODO => 'Sin periodo',
    ];

    /**
     * Nombre de la tabla asociada al modelo.
     * @var string
     */
    protected $table = 'gastos';

    /**
     * Atributos asignables masivamente.
     * @var array<int, string>
     */
    protected $fillable = [
        'creado_por',
        'descripcion',
        'fecha',
        'monto',
        'estatus',
        'id_periodo',
        'id_categoria',
        'id_forma_pago',
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

        return self::ESTATUS_DESCRIPCIONES[(int) $estatus]
            ?? self::ESTATUS_DESCRIPCIONES[self::ESTATUS_ACTIVO];
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

    /**
     * Define la relación con el modelo Categoria.
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    /**
     * Define la relación con el modelo FormaPago.
     */
    public function formaPago()
    {
        return $this->belongsTo(FormaPago::class, 'id_forma_pago');
    }
}
