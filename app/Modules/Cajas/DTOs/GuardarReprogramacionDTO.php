<?php

namespace App\Modules\Cajas\DTOs;

class GuardarReprogramacionDTO
{
    public function __construct(
        public int $tarjetaInscripcionId,
        public string $nuevaFechaVencimiento,
        public string $motivo,
        public string $autorizadoPor,
        public ?int $usuarioId = null
    ) {}

    public static function fromRequest(array $data, ?int $usuarioId = null): self
    {
        return new self(
            tarjetaInscripcionId: (int) $data['tarjeta_inscripcion_id'],
            nuevaFechaVencimiento: $data['nueva_fecha_vencimiento'],
            motivo: trim($data['motivo']),
            autorizadoPor: trim($data['autorizado_por']),
            usuarioId: $usuarioId
        );
    }
}
