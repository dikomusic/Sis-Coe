<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas';

    protected $fillable = [
        'ci',
        'complemento',
        'nombres',
        'paterno',
        'materno',
        'fecha_nacimiento',
        'genero',
        'tipo_sangre',
        'telf_cel',
        'direccion',
        'foto',
        'tipo_persona'
    ];

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombres} {$this->paterno} {$this->materno}");
    }

    public function getCiCompletoAttribute(): string
    {
        return $this->ci . ($this->complemento ? '-' . $this->complemento : '');
    }

    public function esSocioTitular(): bool
    {
        return $this->tipo_persona === 'SOCIO_TITULAR' || $this->socio()->exists();
    }

    public function esBeneficiario(): bool
    {
        return $this->tipo_persona === 'BENEFICIARIO' || $this->beneficiario()->exists();
    }

    public function socio()
    {
        return $this->hasOne(SocioTitular::class, 'persona_id');
    }

    public function socioTitular()
    {
        return $this->socio();
    }

    public function beneficiario()
    {
        return $this->hasOne(BeneficiarioTitular::class, 'persona_id');
    }

    public function beneficiarioTitular()
    {
        return $this->beneficiario();
    }

    public function particular()
    {
        return $this->hasOne(Particular::class, 'persona_id');
    }

    public function instructor()
    {
        return $this->hasOne(Instructor::class, 'persona_id');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'persona_id');
    }

    public function getTipoClienteAttribute(): string
    {
        if ($this->socio) return 'SOCIO';
        if ($this->beneficiario) return 'BENEFICIARIO';
        return 'PARTICULAR';
    }
}

