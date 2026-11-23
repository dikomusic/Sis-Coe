<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    protected $table = 'disciplinas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo'
    ];

    public function modalidades()
    {
        return $this->hasMany(Modalidad::class, 'disciplina_id');
    }

    public function horarios()
    {
        return $this->hasMany(HorarioCurso::class, 'disciplina_id');
    }

    public function horariosCursos()
    {
        return $this->hasMany(HorarioCurso::class, 'disciplina_id');
    }
}

