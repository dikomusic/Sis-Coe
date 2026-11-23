<?php

namespace App\Shared\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

/**
 * Servicio global de códigos QR institucional para todo el ERP
 */
class QrCodeService
{
    /**
     * Generar código QR inline en formato vectorial SVG
     */
    public function generarSvg(string $contenido, int $size = 140): string
    {
        return QrCode::size($size)->generate($contenido);
    }

    /**
     * Generar token único UUID v4 para escaneo seguro
     */
    public function generarUuid(): string
    {
        return (string) Str::uuid();
    }
}
