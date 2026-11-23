<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    protected $table = 'grados';

    protected $fillable = [
        'nombre',
        'abreviatura'
    ];

    public function getDescripcionAttribute(): string
    {
        return $this->abreviatura ?? $this->nombre;
    }
}
