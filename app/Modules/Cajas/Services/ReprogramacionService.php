<?php

namespace App\Modules\Cajas\Services;

use App\Modules\Cajas\DTOs\GuardarReprogramacionDTO;
use App\Models\TarjetaInscripcion;
use App\Models\Reprogramacion;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class ReprogramacionService
{
    /**
     * Extender la fecha de vencimiento de una tarjeta y auditar la reprogramación.
     */
    public function reprogramarTarjeta(GuardarReprogramacionDTO $dto): Reprogramacion
    {
        return DB::transaction(function () use ($dto) {
            $tarjeta = TarjetaInscripcion::with('inscripcion')->findOrFail($dto->tarjetaInscripcionId);

            $fechaAnterior = $tarjeta->fecha_vencimiento ? $tarjeta->fecha_vencimiento->toDateString() : now()->toDateString();
            $nuevaFecha = Carbon::parse($dto->nuevaFechaVencimiento)->toDateString();

            if ($nuevaFecha <= $fechaAnterior) {
                throw new Exception("La nueva fecha de vencimiento ({$nuevaFecha}) debe ser posterior a la fecha actual ({$fechaAnterior}).");
            }

            // 1. Crear registro histórico de reprogramación
            $reprogramacion = Reprogramacion::create([
                'inscripcion_id' => $tarjeta->inscripcion_id,
                'fecha_anterior_vencimiento' => $fechaAnterior,
                'nueva_fecha_vencimiento' => $nuevaFecha,
                'clases_reprogramadas' => $tarjeta->asistencias_permitidas - $tarjeta->asistencias_utilizadas,
                'motivo' => $dto->motivo,
                'autorizado_por' => $dto->autorizadoPor,
                'usuario_id' => $dto->usuarioId,
            ]);

            // 2. Actualizar fecha de vencimiento y estado en la tarjeta
            $tarjeta->fecha_vencimiento = $nuevaFecha;
            $tarjeta->estado = 'ACTIVA'; // Se reactiva si estaba vencida
            $tarjeta->save();

            // 3. Actualizar fecha_fin en la inscripción principal
            if ($tarjeta->inscripcion) {
                $tarjeta->inscripcion->fecha_fin = $nuevaFecha;
                $tarjeta->inscripcion->save();
            }

            return $reprogramacion;
        });
    }
}
