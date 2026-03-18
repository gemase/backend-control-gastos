<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Gasto;

class Presupuesto extends Model
{
    protected $table = 'presupuestos';

    protected $fillable = [
        'creado_por',
        'id_periodo',
        'id_categoria',
        'monto',
    ];

    protected $appends = ['gastado', 'disponible'];

    /**
     * Calcula el total gastado en este presupuesto.
     * Si id_categoria es NULL, suma todos los gastos del periodo.
     */
    public function getGastadoAttribute(): float
    {
        $query = Gasto::where('creado_por', $this->creado_por)
            ->where('id_periodo', $this->id_periodo)
            ->where('estatus', Gasto::ESTATUS_ACTIVO);

        if (!is_null($this->id_categoria)) {
            $query->where('id_categoria', $this->id_categoria);
        }

        return (float) $query->sum('monto');
    }

    /**
     * Calcula el monto disponible (puede ser negativo si se superó el presupuesto).
     * Retorna null si el presupuesto no ha sido configurado (monto = 0).
     */
    public function getDisponibleAttribute(): ?float
    {
        if ((float) $this->monto === 0.0) {
            return null;
        }

        return (float) $this->monto - $this->gastado;
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'id_periodo');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }
}
