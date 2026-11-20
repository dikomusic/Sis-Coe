<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocioTitular extends Model
{
    protected $table = 'socio_titular';
    protected $primaryKey = 'id_titular';

    protected $fillable = [
        'boleta_pago',
        'codigo_cossmil',
        'grado',
        'arma_especialidad',
        'situacion_actual',
        'paterno',
        'materno',
        'nombres',
        'ci',
        'fecha_nacimiento',
        'tipo_sangre',
        'direccion',
        'telf_cel',
        'foto',
        'destino',
        'fecha_alta_ejercito',
        'fecha_alta_coe',
        'estado_socio'
    ];

    //para respaldo documentos
    public function documentos()
    {
        return $this->morphMany(\App\Models\DocumentoRespaldo::class, 'documentable');
    }

    // En SocioTitular.php y BeneficiarioTitular.php
    public function credenciales()
    {
        return $this->morphMany(Credencial::class, 'id', 'entidad_type', 'entidad_id');
    }

    //public function documentos()
   // {
   //     return $this->morphMany(DocumentoRespaldo::class, 'documentable');
   // }

    // SOLO FOTO COSSMIL
    public function fotoCossmil()
    {
        return $this->morphOne(DocumentoRespaldo::class, 'documentable')
            ->where('tipo_documento', 'COSSMI');
    }


}
