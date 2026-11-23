<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modalidad extends Model
{
    protected $table = 'modalidades';

    protected $fillable = [
        'disciplina_id',
        'nombre',
        'cantidad_clases',
        'frecuencia_semanal',
        'activo'
    ];

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'disciplina_id');
    }

    public function tarifas()
    {
        return $this->hasMany(Tarifa::class, 'modalidad_id');
    }
}

