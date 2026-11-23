<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reprogramacion extends Model
{
    protected $table = 'reprogramaciones';

    protected $fillable = [
        'inscripcion_id',
        'fecha_anterior_vencimiento',
        'nueva_fecha_vencimiento',
        'clases_reprogramadas',
        'motivo',
        'hoja_ruta',
        'autorizado_por',
        'usuario_id'
    ];

    protected $casts = [
        'fecha_anterior_vencimiento' => 'date',
        'nueva_fecha_vencimiento' => 'date',
    ];

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'inscripcion_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}

