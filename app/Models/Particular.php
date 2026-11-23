<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Particular extends Model
{
    protected $table = 'particulares';

    protected $fillable = [
        'persona_id',
        'codigo_particular',
        'estado'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    /**
     * Generar código correlativo único (ej: PART-00001)
     */
    public static function generarCodigo(): string
    {
        $ultimo = static::max('id') ?? 0;
        return 'PART-' . str_pad($ultimo + 1, 5, '0', STR_PAD_LEFT);
    }
}

