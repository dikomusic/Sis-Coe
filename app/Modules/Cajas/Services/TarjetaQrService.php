<?php

namespace App\Modules\Cajas\Services;

use App\Models\TarjetaInscripcion;
use App\Models\AsistenciaTarjeta;
use App\Shared\Services\QrCodeService;

class TarjetaQrService
{
    public function __construct(
        protected QrCodeService $qrService
    ) {}

    /**
     * Generar correlativo de tarjeta institucional: COE-YYYY-XXXXX
     */
    public function generarCodigoTarjeta(): string
    {
        $year = date('Y');
        $ultimoId = TarjetaInscripcion::max('id') ?? 0;
        $correlativo = str_pad($ultimoId + 1, 5, '0', STR_PAD_LEFT);
        return "COE-{$year}-{$correlativo}";
    }

    /**
     * Generar UUID seguro para el QR de la tarjeta delegando en el servicio global
     */
    public function generarTokenQr(): string
    {
        return $this->qrService->generarUuid();
    }

    /**
     * Renderizar el SVG del código QR delegando en el servicio global
     */
    public function generarSvgQr(string $codigoQr, int $size = 140): string
    {
        return $this->qrService->generarSvg($codigoQr, $size);
    }

    /**
     * Inicializar las casillas físicas/digitales de asistencia para la tarjeta
     */
    public function inicializarCasillasAsistencia(TarjetaInscripcion $tarjeta, int $totalClases = 12): void
    {
        // Las casillas se renderizan visualmente en la plantilla física (Cara 2).
        // Los registros en la tabla asistencias_tarjeta se crean dinámicamente cuando el alumno asiste y escanea su QR.
    }
}
