<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     * @var string
     */
    protected $table = 'periodos';

    /**
     * Atributos asignables masivamente.
     * @var array<int, string>
     */
    protected $fillable = [
        'creado_por',
        'mes',
        'fecha_inicio',
        'fecha_fin',
    ];

    /**
     * Atributos calculados que se agregan al serializar el modelo.
     * @var array<int, string>
     */
    protected $appends = [
        'mes_periodo',
    ];

    /**
     * Devuelve una etiqueta con mes y rango del periodo.
     */
    public function getMesPeriodoAttribute(): string
    {
        return $this->mes . ' | ' . $this->fecha_inicio . ' - ' . $this->fecha_fin;
    }

    /**
     * Define la relación con el modelo User.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }
}
