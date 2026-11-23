<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioCurso extends Model
{
    protected $table = 'horarios_cursos';

    protected $fillable = [
        'disciplina_id',
        'modalidad_id',
        'instructor_id',
        'dias',
        'hora_inicio',
        'hora_fin',
        'capacidad_maxima',
        'activo'
    ];

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'disciplina_id');
    }

    public function modalidad()
    {
        return $this->belongsTo(Modalidad::class, 'modalidad_id');
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'horario_curso_id');
    }

    public function inscripcionesActivas()
    {
        return $this->hasMany(Inscripcion::class, 'horario_curso_id')->where('estado', 'ACTIVA');
    }

    public function getInscritosCountAttribute(): int
    {
        return $this->inscripcionesActivas()->count();
    }

    public function getCupoActualAttribute(): int
    {
        return $this->inscritos_count;
    }

    public function getCupoMaximoAttribute(): int
    {
        return (int) $this->capacidad_maxima;
    }

    public function getDiasSemanaAttribute(): ?string
    {
        return $this->dias;
    }

    public function getCuposDisponiblesAttribute(): int
    {
        return max(0, $this->capacidad_maxima - $this->inscritos_count);
    }

    public function tieneCupo(): bool
    {
        return $this->cupos_disponibles > 0;
    }
}

