<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $table = 'instructores';

    protected $fillable = [
        'persona_id',
        'especialidad',
        'estado'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function horarios()
    {
        return $this->hasMany(HorarioCurso::class, 'instructor_id');
    }

    public function getNombreCompletoAttribute(): string
    {
        return $this->persona ? $this->persona->nombre_completo : 'Instructor #' . $this->id;
    }
}

