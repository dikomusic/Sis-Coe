<?php

namespace App\Shared\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Servicio global de gestión de archivos y fotografías para todo el ERP
 */
class FileUploaderService
{
    /**
     * Guardar archivo o fotografía con nombre normalizado
     */
    public function subir(UploadedFile $archivo, string $carpeta = 'uploads', ?string $nombreBase = null): string
    {
        $base = $nombreBase ? Str::slug($nombreBase) : Str::random(16);
        $ext = $archivo->getClientOriginalExtension();
        $nombreFinal = "{$base}.{$ext}";

        $contador = 1;
        while (Storage::disk('public')->exists("{$carpeta}/{$nombreFinal}")) {
            $nombreFinal = "{$base}_{$contador}.{$ext}";
            $contador++;
        }

        return $archivo->storeAs($carpeta, $nombreFinal, 'public');
    }

    /**
     * Eliminar archivo del disco público si existe
     */
    public function eliminar(?string $ruta): bool
    {
        if ($ruta && Storage::disk('public')->exists($ruta)) {
            return Storage::disk('public')->delete($ruta);
        }
        return false;
    }
}
