<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'inscripciones';

    protected $fillable = [
        'codigo_inscripcion',
        'persona_id',
        'horario_curso_id',
        'tarifa_id',
        'fecha_inscripcion',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'usuario_creador_id'
    ];

    protected $casts = [
        'fecha_inscripcion' => 'date',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function horarioCurso()
    {
        return $this->belongsTo(HorarioCurso::class, 'horario_curso_id');
    }

    public function tarifa()
    {
        return $this->belongsTo(Tarifa::class, 'tarifa_id');
    }

    public function pago()
    {
        return $this->hasOne(PagoFactura::class, 'inscripcion_id');
    }

    public function tarjeta()
    {
        return $this->hasOne(TarjetaInscripcion::class, 'inscripcion_id');
    }

    public function reprogramaciones()
    {
        return $this->hasMany(Reprogramacion::class, 'inscripcion_id');
    }

    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'usuario_creador_id');
    }

    public static function generarCodigo(): string
    {
        $year = date('Y');
        $ultimo = static::whereYear('created_at', $year)->max('id') ?? 0;
        return 'INS-' . $year . '-' . str_pad($ultimo + 1, 5, '0', STR_PAD_LEFT);
    }
}

