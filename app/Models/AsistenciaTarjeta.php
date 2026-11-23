<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsistenciaTarjeta extends Model
{
    protected $table = 'asistencias_tarjeta';

    protected $fillable = [
        'tarjeta_id',
        'fecha_hora',
        'registrada_por',
        'observacion'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

    public function tarjeta()
    {
        return $this->belongsTo(TarjetaInscripcion::class, 'tarjeta_id');
    }

    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrada_por');
    }
}

