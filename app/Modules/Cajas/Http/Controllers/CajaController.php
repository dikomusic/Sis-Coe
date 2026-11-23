<?php

namespace App\Modules\Cajas\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Cajas\Contracts\PersonaRepositoryInterface;
use App\Modules\Cajas\Contracts\InscripcionRepositoryInterface;
use App\Modules\Cajas\Contracts\HorarioCursoRepositoryInterface;
use App\Modules\Cajas\Services\InscripcionService;
use App\Modules\Cajas\Services\TarjetaQrService;
use App\Modules\Cajas\Services\ReprogramacionService;
use App\Modules\Cajas\DTOs\CrearInscripcionDTO;
use App\Modules\Cajas\DTOs\RegistrarParticularDTO;
use App\Modules\Cajas\DTOs\GuardarReprogramacionDTO;
use App\Modules\Cajas\Http\Requests\GuardarInscripcionRequest;
use App\Modules\Cajas\Http\Requests\RegistrarParticularRequest;
use App\Modules\Cajas\Http\Requests\GuardarReprogramacionRequest;
use App\Models\Inscripcion;
use App\Models\Persona;
use App\Models\TarjetaInscripcion;
use App\Models\Tarifa;
use App\Models\HorarioCurso;
use App\Models\Disciplina;
use App\Models\Reprogramacion;
use App\Models\PagoFactura;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Exception;

class CajaController extends Controller
{
    public function __construct(
        protected PersonaRepositoryInterface $personaRepo,
        protected InscripcionRepositoryInterface $inscripcionRepo,
        protected HorarioCursoRepositoryInterface $horarioRepo,
        protected InscripcionService $inscripcionService,
        protected TarjetaQrService $qrService,
        protected ReprogramacionService $reprogramacionService
    ) {}

    /**
     * Dashboard general de Cajas
     */
    public function index(): View
    {
        $hoy = now()->toDateString();

        $stats = [
            'total_recaudado_hoy' => (float) (PagoFactura::whereDate('created_at', $hoy)->sum('importe_final') ?? 0),
            'inscripciones_hoy' => Inscripcion::whereDate('created_at', $hoy)->count(),
            'descuentos_hoy' => (float) (PagoFactura::whereDate('created_at', $hoy)->sum('monto_descuento') ?? 0),
            'cajero_nombre' => auth()->user()->name ?? 'Cajero COE',
        ];

        // Monitor de cupos en vivo: Cursos con sus aforos calculados
        $horariosCursos = HorarioCurso::where('activo', true)
            ->with(['disciplina', 'instructor.persona'])
            ->withCount('inscripcionesActivas')
            ->get()
            ->map(function ($h) {
                $capacidad = (int) ($h->capacidad_maxima ?? 20);
                $ocupados = (int) ($h->inscripciones_activas_count ?? 0);
                $disponibles = max(0, $capacidad - $ocupados);
                $pct = $capacidad > 0 ? min(100, round(($ocupados / $capacidad) * 100)) : 0;
                
                return [
                    'id' => $h->id,
                    'disciplina_id' => $h->disciplina_id,
                    'disciplina' => $h->disciplina?->nombre ?? 'N/A',
                    'rango_horario' => substr($h->hora_inicio, 0, 5) . ' - ' . substr($h->hora_fin, 0, 5),
                    'dias' => $h->dias_semana ?? $h->dias,
                    'instructor' => $h->instructor?->persona ? $h->instructor->persona->nombre_completo : ($h->instructor?->especialidad ?? 'COE'),
                    'capacidad' => $capacidad,
                    'ocupados' => $ocupados,
                    'disponibles' => $disponibles,
                    'porcentaje' => $pct,
                    'estado_cupo' => $disponibles <= 0 ? 'AGOTADO' : ($disponibles <= 3 ? 'CRITICO' : 'DISPONIBLE'),
                ];
            })
            ->sortBy([
                ['disponibles', 'asc'],
                ['porcentaje', 'desc']
            ])
            ->values();

        $disciplinas = Disciplina::where('activo', true)->orderBy('nombre')->get();

        $ultimasInscripciones = Inscripcion::with(['persona', 'horarioCurso.disciplina', 'pago', 'tarjeta'])
            ->latest('id')
            ->limit(10)
            ->get();

        return view('pages.cajas.dashboard', compact('stats', 'horariosCursos', 'disciplinas', 'ultimasInscripciones'));
    }

    /**
     * Ventanilla principal de inscripción (Proceso guiado)
     */
    public function ventanilla(Request $request): View
    {
        $disciplinas = Cache::remember('ventanilla_disciplinas', 300, function () {
            return Disciplina::where('activo', true)->get();
        });

        $tarifas = Cache::remember('ventanilla_tarifas', 300, function () {
            return Tarifa::where('activo', true)->with('modalidad')->get();
        });

        // Pre-cargar horarios con aforos para respuesta instantánea (0ms) en ventanilla
        $horarios = HorarioCurso::where('activo', true)
            ->with(['instructor.persona', 'modalidad'])
            ->withCount('inscripcionesActivas')
            ->get();

        $horariosPorDisciplina = $horarios->groupBy('disciplina_id')->map(function ($items) {
            return $items->map(function ($h) {
                $nombreInstructor = 'Sin Asignar';
                if ($h->instructor) {
                    $nombreInstructor = $h->instructor->persona 
                        ? $h->instructor->persona->nombre_completo 
                        : ($h->instructor->especialidad ?? 'Instructor COE');
                }
                return [
                    'id' => $h->id,
                    'dias' => $h->dias_semana ?? $h->dias,
                    'hora_inicio' => substr($h->hora_inicio, 0, 5),
                    'hora_fin' => substr($h->hora_fin, 0, 5),
                    'rango_horario' => substr($h->hora_inicio, 0, 5) . ' - ' . substr($h->hora_fin, 0, 5),
                    'instructor' => $nombreInstructor,
                    'modalidad' => $h->modalidad ? $h->modalidad->nombre : 'Regular',
                    'cupo_maximo' => (int) $h->capacidad_maxima,
                    'cupo_actual' => (int) $h->inscripciones_activas_count,
                    'cupos_disponibles' => max(0, (int) $h->capacidad_maxima - (int) $h->inscripciones_activas_count),
                ];
            });
        });

        $ultimoPagoId = PagoFactura::max('id') ?? 0;
        $numeroFacturaSugerido = 'REC-' . date('Ymd') . '-' . str_pad($ultimoPagoId + 1, 4, '0', STR_PAD_LEFT);

        // Preselección opcional desde Dashboard u otros accesos
        $personaPreseleccionada = null;
        if ($request->filled('persona_id')) {
            $p = Persona::with(['socioTitular.grado', 'beneficiarioTitular', 'particular'])->find($request->get('persona_id'));
            if ($p) {
                $ultimaInscripcion = $p->inscripciones()->with(['horarioCurso.disciplina', 'tarifa'])->latest('id')->first();
                $personaPreseleccionada = [
                    'id' => $p->id,
                    'nombre_completo' => $p->nombre_completo,
                    'ci' => $p->ci_completo,
                    'tipo_persona' => $p->tipo_persona,
                    'grado' => $p->socioTitular?->grado?->descripcion ?? null,
                    'es_socio' => $p->esSocioTitular(),
                    'es_beneficiario' => $p->esBeneficiario(),
                    'ultimo_curso' => $ultimaInscripcion ? [
                        'disciplina_id' => $ultimaInscripcion->horarioCurso?->disciplina_id,
                        'disciplina_nombre' => $ultimaInscripcion->horarioCurso?->disciplina?->nombre,
                        'horario_id' => $ultimaInscripcion->horario_curso_id,
                        'tarifa_id' => $ultimaInscripcion->tarifa_id,
                    ] : null,
                ];
            }
        }

        return view('pages.cajas.ventanilla', compact(
            'disciplinas', 
            'tarifas', 
            'horariosPorDisciplina', 
            'numeroFacturaSugerido',
            'personaPreseleccionada'
        ));
    }

    /**
     * Búsqueda predictiva de personas por CI o nombres/apellidos
     */
    public function buscarPersona(Request $request): JsonResponse
    {
        $termino = trim($request->get('q', ''));
        if (strlen($termino) < 2) {
            return response()->json([]);
        }

        $personas = $this->personaRepo->buscarPorCiONombre($termino, 15);

        $resultado = $personas->map(function ($p) {
            $ultimaInscripcion = $p->inscripciones()->with(['tarjeta', 'horarioCurso.disciplina'])->latest('id')->first();
            $tarjeta = $ultimaInscripcion?->tarjeta;

            return [
                'id' => $p->id,
                'nombre_completo' => $p->nombre_completo,
                'ci' => $p->ci_completo,
                'tipo_persona' => $p->tipo_persona,
                'grado' => $p->socioTitular?->grado?->descripcion ?? null,
                'telefono' => $p->telefono,
                'email' => $p->email,
                'es_socio' => $p->esSocioTitular(),
                'es_beneficiario' => $p->esBeneficiario(),
                'ultima_disciplina' => $ultimaInscripcion?->horarioCurso?->disciplina?->nombre ?? null,
                'ultimo_curso' => $ultimaInscripcion ? [
                    'disciplina_id' => $ultimaInscripcion->horarioCurso?->disciplina_id,
                    'disciplina_nombre' => $ultimaInscripcion->horarioCurso?->disciplina?->nombre,
                    'horario_id' => $ultimaInscripcion->horario_curso_id,
                    'tarifa_id' => $ultimaInscripcion->tarifa_id,
                ] : null,
                'tarjeta_codigo' => $tarjeta?->codigo_tarjeta ?? null,
                'tarjeta_id' => $tarjeta?->id,
                'tarjeta_activa' => $tarjeta ? $tarjeta->esValida() : false,
                'tarjeta_vence' => $tarjeta?->fecha_vencimiento ? $tarjeta->fecha_vencimiento->format('d/m/Y') : null,
                'dias_restantes' => $tarjeta ? $tarjeta->diasRestantes() : 0,
                'asistencias_restantes' => $tarjeta ? max(0, $tarjeta->asistencias_permitidas - $tarjeta->asistencias_utilizadas) : 0,
            ];
        });

        return response()->json($resultado);
    }

    /**
     * Obtener horarios filtrados por disciplina
     */
    public function obtenerHorariosPorDisciplina(int $disciplinaId): JsonResponse
    {
        $horarios = $this->horarioRepo->obtenerPorDisciplina($disciplinaId);

        $resultado = $horarios->map(function ($h) {
            $nombreInstructor = 'Sin Asignar';
            if ($h->instructor) {
                $nombreInstructor = $h->instructor->persona 
                    ? $h->instructor->persona->nombre_completo 
                    : ($h->instructor->especialidad ?? 'Instructor COE');
            }

            return [
                'id' => $h->id,
                'dias' => $h->dias_semana ?? $h->dias,
                'hora_inicio' => substr($h->hora_inicio, 0, 5),
                'hora_fin' => substr($h->hora_fin, 0, 5),
                'rango_horario' => substr($h->hora_inicio, 0, 5) . ' - ' . substr($h->hora_fin, 0, 5),
                'instructor' => $nombreInstructor,
                'modalidad' => $h->modalidad ? $h->modalidad->nombre : 'Regular',
                'cupo_maximo' => $h->cupo_maximo,
                'cupo_actual' => $h->cupo_actual,
                'cupos_disponibles' => $h->cupos_disponibles,
            ];
        });

        return response()->json($resultado);
    }

    /**
     * Registrar a un particular al vuelo
     */
    public function registrarParticular(RegistrarParticularRequest $request): JsonResponse
    {
        try {
            $dto = RegistrarParticularDTO::fromRequest($request->validated());
            $persona = $this->personaRepo->crearParticular($dto);

            return response()->json([
                'success' => true,
                'message' => 'Particular registrado con éxito.',
                'persona' => [
                    'id' => $persona->id,
                    'nombre_completo' => $persona->nombre_completo,
                    'ci' => $persona->ci_completo,
                    'tipo_persona' => 'PARTICULAR',
                    'telefono' => $persona->telefono,
                ]
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar particular: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Guardar la inscripción y generar comprobante + tarjeta
     */
    public function guardarInscripcion(GuardarInscripcionRequest $request): RedirectResponse
    {
        try {
            $dto = CrearInscripcionDTO::fromRequest(
                $request->validated(),
                Auth::id()
            );

            $inscripcion = $this->inscripcionService->registrarInscripcion($dto);

            Cache::forget("cajas_stats_" . now()->toDateString());
            Cache::forget('admin_dashboard_stats');

            return redirect()->route('cajas.tarjeta', ['id' => $inscripcion->tarjeta->id])
                ->with('success', "Inscripción {$inscripcion->codigo_inscripcion} registrada exitosamente.");
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Error al procesar la inscripción: ' . $e->getMessage());
        }
    }

    /**
     * Guardar lote de inscripciones (Familiares / Hermanos) en una sola operación POS
     */
    public function guardarLoteInscripciones(Request $request): JsonResponse
    {
        $items = $request->input('items', []);
        $numeroFactura = trim($request->input('numero_factura', ''));

        if (empty($items) || !is_array($items)) {
            return response()->json([
                'success' => false,
                'message' => 'No se proporcionaron alumnos en la bandeja para cobrar.',
            ], 422);
        }

        if (empty($numeroFactura)) {
            return response()->json([
                'success' => false,
                'message' => 'Debe ingresar el número de factura o recibo oficial.',
            ], 422);
        }

        try {
            $resultado = $this->inscripcionService->registrarLoteInscripciones(
                $items,
                Auth::id() ?? 1,
                $numeroFactura
            );

            Cache::forget("cajas_stats_" . now()->toDateString());
            Cache::forget('admin_dashboard_stats');

            return response()->json([
                'success' => true,
                'message' => "¡Se registraron {$resultado['total_inscripciones']} inscripciones correctamente!",
                'data' => $resultado,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el lote: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Vista de la Tarjeta Institucional de Control Físico (Cara 1 y Cara 2)
     */
    public function verTarjeta(int $id): View
    {
        $tarjeta = TarjetaInscripcion::with([
            'inscripcion.persona.socioTitular.grado',
            'inscripcion.persona.beneficiarioTitular.socioTitular.grado',
            'inscripcion.horarioCurso.disciplina',
            'inscripcion.horarioCurso.instructor',
            'inscripcion.horarioCurso.modalidad',
            'inscripcion.pago',
            'asistencias'
        ])->findOrFail($id);

        // Generar el SVG del QR
        $qrSvg = $this->qrService->generarSvgQr($tarjeta->codigo_qr, 135);

        return view('pages.cajas.tarjeta', compact('tarjeta', 'qrSvg'));
    }

    /**
     * Historial de inscripciones con filtros
     */
    public function historial(Request $request): View
    {
        $filtro = trim($request->get('buscar', ''));
        $estado = $request->get('estado', '');

        $query = Inscripcion::with(['persona', 'horarioCurso.disciplina', 'tarifa', 'pago', 'tarjeta']);

        if (!empty($filtro)) {
            $query->where(function ($q) use ($filtro) {
                $q->where('codigo_inscripcion', 'like', "%{$filtro}%")
                  ->orWhereHas('persona', function ($pq) use ($filtro) {
                      $pq->where('ci', 'like', "%{$filtro}%")
                         ->orWhere('nombres', 'ilike', "%{$filtro}%")
                         ->orWhere('paterno', 'ilike', "%{$filtro}%")
                         ->orWhere('materno', 'ilike', "%{$filtro}%");
                  })
                  ->orWhereHas('pago', function ($pq) use ($filtro) {
                      $pq->where('numero_factura', 'like', "%{$filtro}%");
                  });
            });
        }

        if (!empty($estado)) {
            $query->where('estado', $estado);
        }

        $inscripciones = $query->latest()->paginate(15)->withQueryString();

        return view('pages.cajas.historial', compact('inscripciones', 'filtro', 'estado'));
    }

    /**
     * Horarios y disponibilidad de cupos
     */
    /**
     * Grilla y Monitor de Horarios y Cupos con filtros interactivos y lista de alumnos por turno
     */
    public function horarios(): View
    {
        $disciplinas = Disciplina::where('activo', true)
            ->with(['horariosCursos' => function ($q) {
                $q->where('activo', true)
                  ->with([
                      'instructor.persona', 
                      'modalidad',
                      'inscripciones.persona',
                      'inscripciones.tarjeta',
                      'inscripciones.pago'
                  ])
                  ->withCount('inscripcionesActivas');
            }])
            ->get();

        $todosTurnos = [];
        $instructoresNombres = [];

        foreach ($disciplinas as $d) {
            foreach ($d->horariosCursos as $h) {
                $cupoActual = (int) ($h->inscripciones_activas_count ?? $h->cupo_actual);
                $cupoMax = (int) $h->capacidad_maxima;
                $disponibles = max(0, $cupoMax - $cupoActual);
                $pct = $cupoMax > 0 ? round(($cupoActual / $cupoMax) * 100) : 0;
                $nomProf = $h->instructor?->persona?->nombre_completo ?? ($h->instructor?->especialidad ?? 'Sin Asignar');
                if ($nomProf && $nomProf !== 'Sin Asignar') {
                    $instructoresNombres[$nomProf] = true;
                }

                $estado = 'DISPONIBLE';
                if ($disponibles === 0) {
                    $estado = 'LLENO';
                } elseif ($pct >= 75) {
                    $estado = 'CRITICO';
                }

                // Lista de alumnos del turno (clasificando en ALUMNOS ACTIVOS vs EX-ALUMNOS)
                $alumnosInscritos = $h->inscripciones
                    ->filter(fn($ins) => $ins->persona !== null)
                    ->groupBy('persona_id')
                    ->map(function ($grupo) {
                        $ultimaIns = $grupo->sortByDesc('id')->first();
                        $persona = $ultimaIns->persona;
                        $totalPagadoPersona = $grupo->sum(fn($i) => (float) ($i->pago?->importe_final ?? 0));
                        $tieneActiva = $grupo->contains(fn($i) => $i->estado === 'ACTIVA');

                        $comprobantes = $grupo->map(function ($i) {
                            return [
                                'factura' => $i->pago?->numero_factura ?? 'REC-S/N',
                                'monto' => (float) ($i->pago?->importe_final ?? 0),
                                'fecha' => $i->fecha_inscripcion ? date('d/m/Y', strtotime($i->fecha_inscripcion)) : ($i->created_at ? $i->created_at->format('d/m/Y') : ''),
                                'tarjeta_id' => $i->tarjeta?->id,
                                'tarjeta_codigo' => $i->tarjeta?->codigo_tarjeta,
                                'estado' => $i->estado,
                            ];
                        })->values()->toArray();

                        return [
                            'id' => $persona->id,
                            'persona_id' => $persona->id,
                            'nombre' => $persona->nombre_completo ?? 'Sin Nombre',
                            'ci' => $persona->ci_completo ?? 'S/CI',
                            'tipo_persona' => $persona->tipo_persona ?? 'PARTICULAR',
                            'telefono' => $persona->telf_cel,
                            'es_activo' => $tieneActiva,
                            'estado_vigencia' => $tieneActiva ? 'ACTIVO' : 'EX_ALUMNO',
                            'total_inscripciones' => $grupo->count(),
                            'total_pagado' => $totalPagadoPersona,
                            'factura' => $ultimaIns->pago?->numero_factura ?? 'REC-S/N',
                            'monto' => (float) ($ultimaIns->pago?->importe_final ?? 0),
                            'tarjeta_codigo' => $ultimaIns->tarjeta?->codigo_tarjeta,
                            'tarjeta_id' => $ultimaIns->tarjeta?->id,
                            'estado' => $tieneActiva ? 'ACTIVA' : $ultimaIns->estado,
                            'fecha_inscripcion' => $ultimaIns->fecha_inscripcion ? date('d/m/Y', strtotime($ultimaIns->fecha_inscripcion)) : null,
                            'historial_comprobantes' => $comprobantes,
                        ];
                    })
                    ->values()
                    ->sortBy(fn($a) => ($a['es_activo'] ? '0_' : '1_') . $a['nombre'])
                    ->values()
                    ->toArray();

                // El aforo real en vivo cuenta ÚNICAMENTE a los alumnos con inscripción ACTIVA
                $alumnosActivosCount = collect($alumnosInscritos)->where('es_activo', true)->count();
                $totalExAlumnosCount = collect($alumnosInscritos)->where('es_activo', false)->count();
                $totalHistoricoPersonas = count($alumnosInscritos);
                $totalPagosHistoricos = $h->inscripciones->count();

                $cupoActual = $alumnosActivosCount;
                $disponibles = max(0, $cupoMax - $cupoActual);
                $pct = $cupoMax > 0 ? round(($cupoActual / $cupoMax) * 100) : 0;

                $estado = 'DISPONIBLE';
                if ($disponibles === 0) {
                    $estado = 'LLENO';
                } elseif ($pct >= 75) {
                    $estado = 'CRITICO';
                }

                $todosTurnos[] = [
                    'id' => $h->id,
                    'disciplina_id' => $d->id,
                    'disciplina_nombre' => $d->nombre,
                    'instructor_nombre' => $nomProf,
                    'dias' => $h->dias_semana ?? 'Lun a Vie',
                    'hora_inicio' => substr($h->hora_inicio, 0, 5),
                    'hora_fin' => substr($h->hora_fin, 0, 5),
                    'horario_str' => substr($h->hora_inicio, 0, 5) . ' - ' . substr($h->hora_fin, 0, 5),
                    'cupo_max' => $cupoMax,
                    'cupo_actual' => $cupoActual,
                    'cupos_libres' => $disponibles,
                    'total_activos' => $alumnosActivosCount,
                    'total_ex_alumnos' => $totalExAlumnosCount,
                    'total_historico_personas' => $totalHistoricoPersonas,
                    'total_pagos_historicos' => $totalPagosHistoricos,
                    'porcentaje' => $pct,
                    'estado' => $estado,
                    'modalidad' => $h->modalidad?->nombre ?? 'Regular',
                    'alumnos' => $alumnosInscritos,
                ];
            }
        }

        ksort($instructoresNombres);
        $instructores = array_keys($instructoresNombres);

        return view('pages.cajas.horarios', compact('disciplinas', 'todosTurnos', 'instructores'));
    }

    /**
     * Reprogramaciones de tarjetas
     */
    public function reprogramaciones(): View
    {
        $reprogramaciones = Reprogramacion::with(['inscripcion.persona', 'inscripcion.horarioCurso.disciplina', 'usuario'])
            ->latest()
            ->paginate(15);

        return view('pages.cajas.reprogramaciones', compact('reprogramaciones'));
    }

    /**
     * Guardar reprogramación de tarjeta
     */
    public function guardarReprogramacion(GuardarReprogramacionRequest $request): RedirectResponse
    {
        try {
            $dto = GuardarReprogramacionDTO::fromRequest($request->validated(), Auth::id());
            $this->reprogramacionService->reprogramarTarjeta($dto);

            return back()->with('success', 'Tarjeta reprogramada correctamente.');
        } catch (Exception $e) {
            return back()->with('error', 'Error al reprogramar tarjeta: ' . $e->getMessage());
        }
    }

    /**
     * Arqueo y Reportes financieros de Caja con filtros por Período
     */
    public function reportes(Request $request): View
    {
        $filtro = $request->get('filtro');
        $fecha = $request->get('fecha');
        $disciplinaFiltro = $request->get('disciplina');

        // Determinar el filtro por defecto
        if (!$filtro) {
            if ($fecha) {
                $filtro = 'dia';
            } else {
                $hoyTienePagos = PagoFactura::whereDate('created_at', now()->toDateString())->exists();
                $filtro = $hoyTienePagos ? 'dia' : 'todo';
                $fecha = now()->toDateString();
            }
        } elseif (!$fecha) {
            $fecha = now()->toDateString();
        }

        // Construir consulta base de pagos
        $query = PagoFactura::with([
            'inscripcion.persona',
            'inscripcion.horarioCurso.disciplina',
            'cajero'
        ]);

        if ($disciplinaFiltro) {
            $query->whereHas('inscripcion.horarioCurso.disciplina', function ($q) use ($disciplinaFiltro) {
                $q->where('nombre', $disciplinaFiltro);
            });
        }

        if ($filtro === 'dia') {
            $query->whereDate('created_at', $fecha);
            $etiquetaPeriodo = 'Cierre Diario: ' . date('d/m/Y', strtotime($fecha));
        } elseif ($filtro === 'mes') {
            $mesActual = substr($fecha, 0, 7);
            $query->whereRaw("TO_CHAR(created_at, 'YYYY-MM') = ?", [$mesActual]);
            $etiquetaPeriodo = 'Mes: ' . date('m/Y', strtotime($fecha));
        } else {
            $filtro = 'todo';
            $etiquetaPeriodo = 'Histórico Total Consolidado';
        }

        $pagos = $query->latest('id')->get();

        $totalRecaudado = (float) $pagos->sum('importe_final');
        $totalDescuentos = (float) $pagos->sum('monto_descuento');
        $totalInscripciones = $pagos->count();

        // 1. Agrupado por disciplina
        $porDisciplina = $pagos->groupBy(fn($p) => $p->inscripcion?->horarioCurso?->disciplina?->nombre ?? 'Sin Disciplina')
            ->map(fn($group) => [
                'cantidad' => $group->count(),
                'total' => (float) $group->sum('importe_final'),
            ])->sortByDesc('total');

        // 2. Agrupado por tipo de persona
        $porTipoPersona = $pagos->groupBy(fn($p) => $p->inscripcion?->persona?->tipo_persona ?? 'PARTICULAR')
            ->map(fn($group) => [
                'cantidad' => $group->count(),
                'total' => (float) $group->sum('importe_final'),
            ])->sortByDesc('total');

        // 3. Tendencia de recaudación
        $tendencia = [];
        if ($filtro === 'todo') {
            $porMes = DB::table('pagos_facturas')
                ->selectRaw("TO_CHAR(created_at, 'YYYY-MM') as mes, SUM(importe_final) as total, COUNT(*) as cantidad")
                ->groupBy('mes')
                ->orderBy('mes', 'asc')
                ->get();

            $mesesNombres = [
                '01' => 'Ene', '02' => 'Feb', '03' => 'Mar', '04' => 'Abr',
                '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ago',
                '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dic'
            ];

            foreach ($porMes as $pm) {
                $partes = explode('-', $pm->mes);
                $mesNum = $partes[1] ?? '01';
                $año = $partes[0] ?? '';
                $nom = ($mesesNombres[$mesNum] ?? $mesNum) . ' ' . substr($año, 2);
                $tendencia[] = [
                    'dia' => $nom,
                    'fecha' => $pm->mes,
                    'total' => (float) $pm->total,
                ];
            }
        } else {
            $fechaBase = \Carbon\Carbon::parse($fecha);
            for ($i = 6; $i >= 0; $i--) {
                $diaCarbon = $fechaBase->copy()->subDays($i);
                $diaFecha = $diaCarbon->format('Y-m-d');
                $diaNombre = $diaCarbon->locale('es')->isoFormat('ddd D');
                $subtotal = (float) $pagos->filter(fn($p) => $p->created_at->format('Y-m-d') === $diaFecha)->sum('importe_final');
                $tendencia[] = [
                    'dia' => ucfirst($diaNombre),
                    'fecha' => $diaFecha,
                    'total' => $subtotal,
                ];
            }
        }

        $disciplinas = DB::table('disciplinas')->where('activo', true)->orderBy('nombre')->get();
        $comprobantesPaginados = $pagos->take(100);

        return view('pages.cajas.reportes', compact(
            'pagos',
            'comprobantesPaginados',
            'filtro',
            'fecha',
            'etiquetaPeriodo',
            'disciplinaFiltro',
            'disciplinas',
            'totalRecaudado',
            'totalDescuentos',
            'totalInscripciones',
            'porDisciplina',
            'porTipoPersona',
            'tendencia'
        ));
    }

    /**
     * Padrón de Alumnos y Clientes Únicos del COE
     * Cada persona es única, con el total acumulado de compras, cursos e historial
     */
    public function alumnos(Request $request): View
    {
        $buscar = trim($request->get('search', ''));
        $tipo = $request->get('tipo', 'TODOS');
        $orden = $request->get('orden', 'mas_activos');

        $query = Persona::query()
            ->has('inscripciones')
            ->select('personas.*')
            ->selectSub(
                DB::table('pagos_facturas')
                    ->join('inscripciones', 'inscripciones.id', '=', 'pagos_facturas.inscripcion_id')
                    ->whereColumn('inscripciones.persona_id', 'personas.id')
                    ->selectRaw('COALESCE(SUM(pagos_facturas.importe_final), 0)'),
                'total_invertido'
            )
            ->withCount('inscripciones')
            ->with([
                'inscripciones' => function ($q) {
                    $q->latest('id')->limit(1)->with(['horarioCurso.disciplina', 'pago']);
                }
            ]);

        if (!empty($buscar)) {
            $buscarLimpio = trim($buscar);
            $palabras = array_filter(explode(' ', preg_replace('/\s+/', ' ', $buscarLimpio)));

            $query->where(function ($q) use ($buscarLimpio, $palabras) {
                $q->where('personas.ci', 'ilike', "%{$buscarLimpio}%")
                  ->orWhereRaw("CONCAT_WS(' ', personas.nombres, personas.paterno, personas.materno) ILIKE ?", ["%{$buscarLimpio}%"])
                  ->orWhereRaw("CONCAT_WS(' ', personas.paterno, personas.materno, personas.nombres) ILIKE ?", ["%{$buscarLimpio}%"])
                  ->orWhere('personas.telf_cel', 'like', "%{$buscarLimpio}%");

                if (count($palabras) > 1) {
                    $q->orWhere(function ($subWords) use ($palabras) {
                        foreach ($palabras as $p) {
                            $subWords->where(function ($pw) use ($p) {
                                $pw->where('personas.ci', 'ilike', "%{$p}%")
                                   ->orWhereRaw("CONCAT_WS(' ', personas.nombres, personas.paterno, personas.materno) ILIKE ?", ["%{$p}%"]);
                            });
                        }
                    });
                }
            });
        }

        if ($tipo !== 'TODOS') {
            if ($tipo === 'SOCIO') {
                $query->whereIn('personas.tipo_persona', ['SOCIO', 'SOCIO_TITULAR']);
            } elseif ($tipo === 'BENEFICIARIO') {
                $query->where('personas.tipo_persona', 'BENEFICIARIO');
            } elseif ($tipo === 'PARTICULAR') {
                $query->where('personas.tipo_persona', 'PARTICULAR');
            }
        }

        if ($orden === 'alfabetico') {
            $query->orderBy('personas.paterno', 'asc')->orderBy('personas.nombres', 'asc');
        } elseif ($orden === 'recientes') {
            $query->latest('personas.id');
        } elseif ($orden === 'mas_inversion') {
            $query->orderByDesc('total_invertido');
        } else {
            $query->orderByDesc('inscripciones_count')->latest('personas.id');
        }

        $alumnos = $query->paginate(20)->withQueryString();

        // KPIs consolidados
        $totalAlumnosUnicos = Persona::has('inscripciones')->count();
        $totalParticularesUnicos = Persona::has('inscripciones')->where('tipo_persona', 'PARTICULAR')->count();
        $totalSociosUnicos = Persona::has('inscripciones')->whereIn('tipo_persona', ['SOCIO', 'SOCIO_TITULAR', 'BENEFICIARIO'])->count();
        $totalRecaudacionGeneral = (float) DB::table('pagos_facturas')->sum('importe_final');

        return view('pages.cajas.alumnos.index', compact(
            'alumnos',
            'buscar',
            'tipo',
            'orden',
            'totalAlumnosUnicos',
            'totalParticularesUnicos',
            'totalSociosUnicos',
            'totalRecaudacionGeneral'
        ));
    }

    /**
     * Detalle / Expediente Único de Compras e Inscripciones del Alumno
     */
    public function detalleAlumno(int $id): View
    {
        $alumno = Persona::with(['socio.gradoMilitar', 'beneficiario', 'particular'])->findOrFail($id);

        $inscripciones = Inscripcion::where('persona_id', $id)
            ->with([
                'horarioCurso.disciplina',
                'horarioCurso.modalidad',
                'horarioCurso.instructor.persona',
                'pago',
                'tarjeta',
                'reprogramaciones'
            ])
            ->orderByDesc('id')
            ->get();

        $totalInscripciones = $inscripciones->count();
        $totalInvertido = (float) $inscripciones->sum(fn($i) => (float) ($i->pago?->importe_final ?? 0));
        $disciplinasTomadas = $inscripciones->map(fn($i) => $i->horarioCurso?->disciplina?->nombre)->filter()->unique()->values();
        $primeraInscripcion = $inscripciones->sortBy('id')->first();
        $ultimaInscripcion = $inscripciones->first();

        return view('pages.cajas.alumnos.show', compact(
            'alumno',
            'inscripciones',
            'totalInscripciones',
            'totalInvertido',
            'disciplinasTomadas',
            'primeraInscripcion',
            'ultimaInscripcion'
        ));
    }
}
