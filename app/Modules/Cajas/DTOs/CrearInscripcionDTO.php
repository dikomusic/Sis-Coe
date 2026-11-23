<?php

namespace App\Modules\Cajas\DTOs;

class CrearInscripcionDTO
{
    public function __construct(
        public int $personaId,
        public int $horarioCursoId,
        public int $tarifaId,
        public string $fechaInicio,
        public string $numeroFactura,
        public float $importeBase,
        public ?string $tipoBeneficio = 'NINGUNO',
        public float $descuentoPorcentaje = 0,
        public ?string $motivoDescuento = null,
        public ?string $autorizacionDescuento = null,
        public float $importeFinal = 0,
        public ?int $cajeroId = null
    ) {}

    public static function fromRequest(array $data, ?int $cajeroId = null): self
    {
        return new self(
            personaId: (int) $data['persona_id'],
            horarioCursoId: (int) $data['horario_curso_id'],
            tarifaId: (int) $data['tarifa_id'],
            fechaInicio: $data['fecha_inicio'],
            numeroFactura: trim($data['numero_factura']),
            importeBase: (float) $data['importe_base'],
            tipoBeneficio: $data['tipo_beneficio'] ?? 'NINGUNO',
            descuentoPorcentaje: (float) ($data['descuento_porcentaje'] ?? 0),
            motivoDescuento: $data['motivo_descuento'] ?? null,
            autorizacionDescuento: $data['autorizacion_descuento'] ?? null,
            importeFinal: (float) $data['importe_final'],
            cajeroId: $cajeroId
        );
    }
}
