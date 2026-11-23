<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TarjetaInscripcion extends Model
{
    protected $table = 'tarjetas_inscripcion';

    protected $fillable = [
        'inscripcion_id',
        'codigo_tarjeta',
        'codigo_qr',
        'fecha_emision',
        'fecha_vencimiento',
        'asistencias_permitidas',
        'asistencias_utilizadas',
        'estado',
        'reimpresiones_count',
        'ultima_reimpresion'
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_vencimiento' => 'date',
        'ultima_reimpresion' => 'datetime',
    ];

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'inscripcion_id');
    }

    public function asistencias()
    {
        return $this->hasMany(AsistenciaTarjeta::class, 'tarjeta_id');
    }

    public function esValida(): bool
    {
        return $this->estado === 'ACTIVA' && ($this->fecha_vencimiento === null || $this->fecha_vencimiento->isFuture() || $this->fecha_vencimiento->isToday());
    }

    public function diasRestantes(): int
    {
        if (!$this->fecha_vencimiento) return 0;
        return max(0, (int) now()->startOfDay()->diffInDays($this->fecha_vencimiento, false));
    }

    public static function generarUuid(): string
    {
        return (string) Str::uuid();
    }
}

