<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoRespaldo extends Model
{
    protected $table = 'documentos_respaldo';

    protected $fillable = [
        'documentable_id',
        'documentable_type',
        'tipo_documento',
        'ruta_archivo',
        'descripcion',
        'creado_por'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relación polimórfica
    public function documentable()
    {
        return $this->morphTo();
    }

    // Opcional: quién subió el documento
    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }
}