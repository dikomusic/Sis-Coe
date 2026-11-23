<?php

namespace App\Modules\Cajas\Services;

use App\Modules\Cajas\DTOs\CrearInscripcionDTO;
use App\Modules\Cajas\Contracts\InscripcionRepositoryInterface;
use App\Modules\Cajas\Contracts\HorarioCursoRepositoryInterface;
use App\Modules\Cajas\Contracts\PersonaRepositoryInterface;
use App\Models\Inscripcion;
use App\Models\PagoFactura;
use App\Models\TarjetaInscripcion;
use App\Models\Tarifa;
use App\Models\HorarioCurso;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class InscripcionService
{
    public function __construct(
        protected InscripcionRepositoryInterface $inscripcionRepo,
        protected HorarioCursoRepositoryInterface $horarioRepo,
        protected PersonaRepositoryInterface $personaRepo,
        protected TarjetaQrService $qrService
    ) {}

    /**
     * Procesar la inscripción completa de un socio, beneficiario o particular en ventanilla.
     */
    public function registrarInscripcion(CrearInscripcionDTO $dto): Inscripcion
    {
        return DB::transaction(function () use ($dto) {
            // 1. Validar Horario y Cupos
            $horario = HorarioCurso::findOrFail($dto->horarioCursoId);
            if (!$horario->tieneCupo()) {
                throw new Exception("El horario seleccionado ya no tiene cupos disponibles ({$horario->inscritos_count}/{$horario->capacidad_maxima}).");
            }

            // 2. Determinar fechas de vigencia (1 mes calendario / 30 días)
            $fechaInicio = Carbon::parse($dto->fechaInicio);
            $fechaFin = (clone $fechaInicio)->addDays(30);

            // 3. Generar código único de inscripción: INS-YYYY-XXXXX
            $year = date('Y');
            $ultimoId = Inscripcion::max('id') ?? 0;
            $codigoInscripcion = "INS-{$year}-" . str_pad($ultimoId + 1, 5, '0', STR_PAD_LEFT);

            // 4. Crear registro de Inscripción
            $inscripcion = Inscripcion::create([
                'codigo_inscripcion' => $codigoInscripcion,
                'persona_id' => $dto->personaId,
                'horario_curso_id' => $dto->horarioCursoId,
                'tarifa_id' => $dto->tarifaId,
                'fecha_inscripcion' => now()->toDateString(),
                'fecha_inicio' => $fechaInicio->toDateString(),
                'fecha_fin' => $fechaFin->toDateString(),
                'estado' => 'ACTIVA',
                'usuario_creador_id' => $dto->cajeroId,
            ]);

            // 5. Calcular montos de descuento
            $montoDescuento = round(($dto->importeBase * ($dto->descuentoPorcentaje / 100)), 2);
            $importeFinalCalculado = round($dto->importeBase - $montoDescuento, 2);

            // 6. Registrar Pago / Factura
            PagoFactura::create([
                'inscripcion_id' => $inscripcion->id,
                'numero_factura' => $dto->numeroFactura,
                'importe_base' => $dto->importeBase,
                'porcentaje_descuento' => $dto->descuentoPorcentaje,
                'monto_descuento' => $montoDescuento,
                'importe_final' => $importeFinalCalculado,
                'tipo_beneficio' => $dto->tipoBeneficio ?? 'NINGUNO',
                'motivo_descuento' => $dto->motivoDescuento,
                'autorizacion_descuento' => $dto->autorizacionDescuento,
                'fecha_pago' => now(),
                'cajero_id' => $dto->cajeroId,
            ]);

            // 7. Generar Tarjeta Institucional de Control Físico/QR
            $codigoTarjeta = $this->qrService->generarCodigoTarjeta();
            $tokenQr = $this->qrService->generarTokenQr();

            $tarjeta = TarjetaInscripcion::create([
                'inscripcion_id' => $inscripcion->id,
                'codigo_tarjeta' => $codigoTarjeta,
                'codigo_qr' => $tokenQr,
                'fecha_emision' => now()->toDateString(),
                'fecha_vencimiento' => $fechaFin->toDateString(),
                'asistencias_permitidas' => 12, // Estándar COE 12 clases por mes
                'asistencias_utilizadas' => 0,
                'estado' => 'ACTIVA',
                'reimpresiones_count' => 0,
            ]);

            // 8. Inicializar casillas de asistencia física y digital
            $this->qrService->inicializarCasillasAsistencia($tarjeta, 12);

            return $inscripcion->load([
                'persona',
                'horarioCurso.disciplina',
                'horarioCurso.instructor',
                'horarioCurso.modalidad',
                'tarifa',
                'pago',
                'tarjeta.asistencias'
            ]);
        });
    }

    /**
     * Calcular desglose de tarifa para una persona según su tipo (Socio, Beneficiario, Particular).
     */
    public function calcularTarifa(int $personaId, int $tarifaId): array
    {
        $persona = $this->personaRepo->buscarPorId($personaId);
        $tarifa = Tarifa::findOrFail($tarifaId);

        $montoBase = (float) $tarifa->costo;
        $descuentoPorcentaje = 0;
        $tipoBeneficio = 'NINGUNO';

        if ($persona) {
            if ($persona->esSocioTitular()) {
                $tipoBeneficio = 'SOCIO_TITULAR';
                // Socios titulares gozan de tarifas preferenciales o descuentos según la tarifa
            } elseif ($persona->esBeneficiario()) {
                $tipoBeneficio = 'BENEFICIARIO_FAMILIAR';
            } else {
                $tipoBeneficio = 'PARTICULAR';
            }
        }

        $montoDescuento = round($montoBase * ($descuentoPorcentaje / 100), 2);
        $montoFinal = round($montoBase - $montoDescuento, 2);

        return [
            'tarifa_id' => $tarifa->id,
            'nombre_tarifa' => $tarifa->nombre,
            'monto_base' => $montoBase,
            'descuento_porcentaje' => $descuentoPorcentaje,
            'monto_descuento' => $montoDescuento,
            'monto_final' => $montoFinal,
            'tipo_beneficio' => $tipoBeneficio,
        ];
    }

    /**
     * Procesar un lote de inscripciones (ej. hermanos o grupo familiar) en una sola transacción atómica.
     *
     * @param array $items Array con datos de cada inscripción
     * @param int $cajeroId ID del usuario cajero
     * @param string $numeroFacturaBase Número de factura o recibo
     * @return array Resumen con inscripciones y tarjetas generadas
     */
    public function registrarLoteInscripciones(array $items, int $cajeroId, string $numeroFacturaBase): array
    {
        return DB::transaction(function () use ($items, $cajeroId, $numeroFacturaBase) {
            $inscripcionesGeneradas = [];
            $tarjetasGeneradas = [];
            $totalCobrado = 0;

            foreach ($items as $index => $item) {
                $personaId = (int) $item['persona_id'];
                $horarioCursoId = (int) $item['horario_curso_id'];
                $tarifaId = (int) $item['tarifa_id'];
                $fechaInicioStr = $item['fecha_inicio'] ?? now()->toDateString();
                $importeBase = (float) ($item['importe_base'] ?? 0);
                $descuentoPorcentaje = (float) ($item['descuento_porcentaje'] ?? 0);
                $motivoDescuento = $item['motivo_descuento'] ?? null;
                $autorizacionDescuento = $item['autorizacion_descuento'] ?? null;
                $tipoBeneficio = $item['tipo_beneficio'] ?? 'PARTICULAR';

                $numFactura = $numeroFacturaBase;

                // 1. Validar Horario y Cupos
                $horario = HorarioCurso::findOrFail($horarioCursoId);
                if (!$horario->tieneCupo()) {
                    throw new Exception("El horario seleccionado para el alumno #" . ($index + 1) . " ya no tiene cupos disponibles.");
                }

                // 2. Fechas de vigencia (30 días)
                $fechaInicio = Carbon::parse($fechaInicioStr);
                $fechaFin = (clone $fechaInicio)->addDays(30);

                // 3. Generar código de inscripción
                $year = date('Y');
                $ultimoId = Inscripcion::max('id') ?? 0;
                $codigoInscripcion = "INS-{$year}-" . str_pad($ultimoId + 1, 5, '0', STR_PAD_LEFT);

                // 4. Crear inscripción
                $inscripcion = Inscripcion::create([
                    'codigo_inscripcion' => $codigoInscripcion,
                    'persona_id' => $personaId,
                    'horario_curso_id' => $horarioCursoId,
                    'tarifa_id' => $tarifaId,
                    'fecha_inscripcion' => now()->toDateString(),
                    'fecha_inicio' => $fechaInicio->toDateString(),
                    'fecha_fin' => $fechaFin->toDateString(),
                    'estado' => 'ACTIVA',
                    'usuario_creador_id' => $cajeroId,
                ]);

                // 5. Calcular importes
                $montoDescuento = round(($importeBase * ($descuentoPorcentaje / 100)), 2);
                $importeFinalCalculado = round($importeBase - $montoDescuento, 2);
                $totalCobrado += $importeFinalCalculado;

                // 6. Registrar Pago
                PagoFactura::create([
                    'inscripcion_id' => $inscripcion->id,
                    'numero_factura' => $numFactura,
                    'importe_base' => $importeBase,
                    'porcentaje_descuento' => $descuentoPorcentaje,
                    'monto_descuento' => $montoDescuento,
                    'importe_final' => $importeFinalCalculado,
                    'tipo_beneficio' => $tipoBeneficio,
                    'motivo_descuento' => $motivoDescuento,
                    'autorizacion_descuento' => $autorizacionDescuento,
                    'fecha_pago' => now(),
                    'cajero_id' => $cajeroId,
                ]);

                // 7. Generar Tarjeta Institucional de Control Físico/QR
                $codigoTarjeta = $this->qrService->generarCodigoTarjeta();
                $tokenQr = $this->qrService->generarTokenQr();

                $tarjeta = TarjetaInscripcion::create([
                    'inscripcion_id' => $inscripcion->id,
                    'codigo_tarjeta' => $codigoTarjeta,
                    'codigo_qr' => $tokenQr,
                    'fecha_emision' => now()->toDateString(),
                    'fecha_vencimiento' => $fechaFin->toDateString(),
                    'asistencias_permitidas' => 12,
                    'asistencias_utilizadas' => 0,
                    'estado' => 'ACTIVA',
                    'reimpresiones_count' => 0,
                ]);

                $this->qrService->inicializarCasillasAsistencia($tarjeta, 12);

                $inscripcion->load(['persona', 'horarioCurso.disciplina']);

                $inscripcionesGeneradas[] = $inscripcion;
                $tarjetasGeneradas[] = [
                    'tarjeta_id' => $tarjeta->id,
                    'codigo_tarjeta' => $tarjeta->codigo_tarjeta,
                    'alumno' => $inscripcion->persona->nombre_completo,
                    'disciplina' => $inscripcion->horarioCurso->disciplina?->nombre ?? 'Curso',
                    'url' => route('cajas.tarjeta', ['id' => $tarjeta->id]),
                ];
            }

            return [
                'total_inscripciones' => count($inscripcionesGeneradas),
                'total_cobrado' => $totalCobrado,
                'numero_factura' => $numeroFacturaBase,
                'inscripciones' => $inscripcionesGeneradas,
                'tarjetas' => $tarjetasGeneradas,
            ];
        });
    }
}
