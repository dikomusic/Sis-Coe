<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Credencial extends Model
{
    protected $table = 'credenciales';

    protected $fillable = [
        'entidad_id',
        'entidad_type',
        'imagen_frontal',
        'imagen_trasera',
        'fecha_emision',
        'fecha_vencimiento',
        'estado',
        'estado_credencial'  // ← AÑADIDO: para guardar el motivo (AFILIACION, BAJA_POR_..., etc.)
    ];

    protected $casts = [
        'fecha_emision'     => 'date',
        'fecha_vencimiento' => 'date',
    ];

    // Relación polimórfica con tus nombres de columna
    public function entidad()
    {
        return $this->morphTo('entidad', 'entidad_type', 'entidad_id');
    }

    // Opcional: método para mostrar motivo bonito
    public function getMotivoAttribute()
    {
        return str_replace('_', ' ', $this->estado_credencial);
    }
}