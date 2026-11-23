<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
    protected $table = 'tarifas';

    protected $fillable = [
        'modalidad_id',
        'tipo_persona',
        'precio',
        'activo'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'activo' => 'boolean'
    ];

    public function modalidad()
    {
        return $this->belongsTo(Modalidad::class, 'modalidad_id');
    }

    public function getCostoAttribute(): float
    {
        return (float) $this->precio;
    }

    public function getNombreAttribute(): string
    {
        return ($this->modalidad?->nombre ?? 'Tarifa General') . ' (' . $this->tipo_persona . ')';
    }
}

