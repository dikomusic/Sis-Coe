<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;


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

    /**
     * Verificar si el beneficiario debe darse de baja por edad (solo hijos)
     * Llama a este método después de guardar o en un comando/artisan
     */
    public function verificarBajaPorEdad()
    {
        // Solo aplica a hijos
        if (!in_array($this->parentesco, ['HIJO', 'HIJA', 'HIJASTRO', 'HIJASTRA'])) {
            return;
        }

        if (!$this->fecha_nacimiento) {
            return;
        }

        // Calcular edad exacta
         $edad = Carbon::parse($this->fecha_nacimiento)->age;

        // Si ya cumplió 28 años o más
        if ($edad >= 25) {
            $this->estado_beneficiario = 'BAJA';
            $this->observacion = 'Baja según normativa de edad vigente.';
            $this->saveQuietly(); // Guarda sin disparar eventos extras
        }
    }


        // En SocioTitular.php y BeneficiarioTitular.php
public function credenciales()
{
    return $this->morphMany(Credencial::class, 'entidad', 'entidad_type', 'entidad_id');
}



}