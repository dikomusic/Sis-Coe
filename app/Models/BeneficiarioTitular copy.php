<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BeneficiarioTitular extends Model
{
    use HasFactory;

    protected $table = 'beneficiario_titular';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_titular',
        'codigo_cossmil',
        'parentesco',
        'paterno',
        'materno',
        'nombres',
        'ci',
        'fecha_nacimiento',
        'tipo_sangre',
        'telf_cel',
        'foto',
        'fecha_alta_coe',
        'estado_beneficiario',     // estado propio del beneficiario
        'estado_por_titular',      // estado heredado del socio titular
        'observacion',
        'nota'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_alta_coe'   => 'date'
    ];

    /**
     * Relación inversa:
     * Un beneficiario pertenece a un único socio titular.
     */
    public function titular()
    {
        return $this->belongsTo(SocioTitular::class, 'id_titular', 'id_titular');
    }

    public function documentos()
    {
        return $this->morphMany(\App\Models\DocumentoRespaldo::class, 'documentable');
    }



}