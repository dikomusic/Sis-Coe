<?php

namespace App\Modules\Cajas\DTOs;

class RegistrarParticularDTO
{
    public function __construct(
        public string $nombres,
        public string $apellidos,
        public string $ci,
        public ?string $complemento = null,
        public ?string $expedido = 'LP',
        public ?string $fechaNacimiento = null,
        public ?string $sexo = null,
        public ?string $telefono = null,
        public ?string $email = null,
        public ?string $direccion = null,
        public ?string $observaciones = null,
        public ?string $contactoEmergenciaNombre = null,
        public ?string $contactoEmergenciaTelefono = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            nombres: strtoupper(trim($data['nombres'])),
            apellidos: strtoupper(trim($data['apellidos'])),
            ci: trim($data['ci']),
            complemento: !empty($data['complemento']) ? strtoupper(trim($data['complemento'])) : null,
            expedido: $data['expedido'] ?? 'LP',
            fechaNacimiento: $data['fecha_nacimiento'] ?? null,
            sexo: $data['sexo'] ?? null,
            telefono: $data['telefono'] ?? null,
            email: $data['email'] ?? null,
            direccion: $data['direccion'] ?? null,
            observaciones: $data['observaciones'] ?? null,
            contactoEmergenciaNombre: $data['contacto_emergencia_nombre'] ?? null,
            contactoEmergenciaTelefono: $data['contacto_emergencia_telefono'] ?? null
        );
    }
}
