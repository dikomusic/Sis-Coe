<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Persona;
use App\Models\Particular;
use App\Models\Disciplina;
use App\Models\Modalidad;
use App\Models\Tarifa;
use App\Models\HorarioCurso;
use App\Models\Inscripcion;
use App\Models\PagoFactura;
use App\Models\TarjetaInscripcion;
use App\Models\Reprogramacion;
use App\Models\SocioTitular;
use App\Models\BeneficiarioTitular;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CajaController extends Controller
{
    /**
     * Dashboard principal del Portal de Cajas
     */
    public function index()
    {
        $hoy = now()->toDateString();

        // Estadísticas de la jornada
        $totalRecaudadoHoy = PagoFactura::whereDate('fecha_pago', $hoy)->sum('importe_final');
        $inscripcionesHoy = Inscripcion::whereDate('fecha_inscripcion', $hoy)->count();
        $particularesHoy = Inscripcion::whereDate('fecha_inscripcion', $hoy)
            ->whereHas('persona', fn($q) => $q->where('tipo_persona', 'PARTICULAR'))
            ->count();
        $sociosHoy = $inscripcionesHoy - $particularesHoy;

        // Últimas inscripciones emitidas
        $ultimasInscripciones = Inscripcion::with(['persona', 'horarioCurso.disciplina', 'pago', 'tarjeta'])
            ->latest()
            ->take(8)
            ->get();

        // Cursos y horarios con cupos críticos (< 5 disponibles)
        $horarios = HorarioCurso::with(['disciplina', 'instructor.persona'])
            ->where('activo', true)
            ->get();

        return view('cajas.index', compact(
            'totalRecaudadoHoy',
            'inscripcionesHoy',
            'particularesHoy',
            'sociosHoy',
            'ultimasInscripciones',
            'horarios'
        ));
    }

    /**
     * Ventanilla de Cobro / Nueva Inscripción
     */
    public function create()
    {
        $disciplinas = Disciplina::where('activo', true)
            ->with(['modalidades' => function($q) {
                $q->where('activo', true)->with('tarifas');
            }, 'horarios' => function($q) {
                $q->where('activo', true)->with('instructor.persona');
            }])
            ->get();

        // Sugerir siguiente número de factura o recibo
        $ultimaFactura = PagoFactura::max('numero_factura');
        $siguienteFactura = is_numeric($ultimaFactura) ? ((int)$ultimaFactura + 1) : '';

        return view('cajas.create', compact('disciplinas', 'siguienteFactura'));
    }

    /**
     * API Buscador rápido de personas (Socios, Beneficiarios, Particulares)
     */
    public function buscarPersona(Request $request)
    {
        $term = trim($request->get('q', ''));
        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $personas = Persona::query()
            ->where(function($q) use ($term) {
                $q->where('ci', 'ilike', "%{$term}%")
                  ->orWhere('nombres', 'ilike', "%{$term}%")
                  ->orWhere('paterno', 'ilike', "%{$term}%")
                  ->orWhere('materno', 'ilike', "%{$term}%");
            })
            ->orWhereHas('socio', function($q) use ($term) {
                $q->where('codigo_cossmil', 'ilike', "%{$term}%")
                  ->orWhere('boleta_pago', 'ilike', "%{$term}%");
            })
            ->orWhereHas('beneficiario', function($q) use ($term) {
                $q->where('codigo_cossmil', 'ilike', "%{$term}%");
            })
            ->orWhereHas('particular', function($q) use ($term) {
                $q->where('codigo_particular', 'ilike', "%{$term}%");
            })
            ->with(['socio', 'beneficiario.titular', 'particular'])
            ->take(10)
            ->get()
            ->map(function($p) {
                $tipo = 'PARTICULAR';
                $codigo = $p->particular ? $p->particular->codigo_particular : ($p->ci ?? 'S/C');
                $extra = '';

                if ($p->socio) {
                    $tipo = 'SOCIO';
                    $codigo = $p->socio->codigo_cossmil ?? $p->socio->boleta_pago;
                    $extra = 'Grado: ' . ($p->socio->grado ?? 'Socio Titular');
                } elseif ($p->beneficiario) {
                    $tipo = 'BENEFICIARIO';
                    $codigo = $p->beneficiario->codigo_cossmil ?? 'Beneficiario';
                    $extra = 'Parentesco: ' . ($p->beneficiario->parentesco ?? 'Familiar');
                }

                return [
                    'id' => $p->id,
                    'nombre_completo' => $p->nombre_completo,
                    'ci' => $p->ci ?? 'Sin CI',
                    'tipo' => $tipo,
                    'codigo' => $codigo,
                    'extra' => $extra,
                    'telefono' => $p->telf_cel ?? ''
                ];
            });

        return response()->json($personas);
    }

    /**
     * API Registrar un cliente PARTICULAR al vuelo en ventanilla
     */
    public function registrarParticular(Request $request)
    {
        $validated = $request->validate([
            'nombres' => 'required|string|max:150',
            'paterno' => 'nullable|string|max:100',
            'materno' => 'nullable|string|max:100',
            'ci' => 'nullable|string|max:50',
            'telf_cel' => 'nullable|string|max:50',
        ]);

        return DB::transaction(function() use ($validated) {
            $persona = Persona::create([
                'nombres' => strtoupper($validated['nombres']),
                'paterno' => !empty($validated['paterno']) ? strtoupper($validated['paterno']) : null,
                'materno' => !empty($validated['materno']) ? strtoupper($validated['materno']) : null,
                'ci' => $validated['ci'] ?? null,
                'telf_cel' => $validated['telf_cel'] ?? null,
                'tipo_persona' => 'PARTICULAR'
            ]);

            $particular = Particular::create([
                'persona_id' => $persona->id,
                'codigo_particular' => Particular::generarCodigo(),
                'estado' => 'ACTIVO'
            ]);

            return response()->json([
                'success' => true,
                'persona' => [
                    'id' => $persona->id,
                    'nombre_completo' => $persona->nombre_completo,
                    'ci' => $persona->ci ?? 'Sin CI',
                    'tipo' => 'PARTICULAR',
                    'codigo' => $particular->codigo_particular,
                    'extra' => 'Cliente Particular Nuevo',
                    'telefono' => $persona->telf_cel
                ]
            ]);
        });
    }

    /**
     * Guardar Inscripción, Pago y emitir Tarjeta
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'persona_id' => 'required|exists:personas,id',
            'horario_curso_id' => 'required|exists:horarios_cursos,id',
            'tarifa_id' => 'required|exists:tarifas,id',
            'fecha_inicio' => 'required|date',
            'numero_factura' => 'required|string|max:50',
            'importe_base' => 'required|numeric|min:0',
            'tipo_beneficio' => 'nullable|string',
            'descuento_porcentaje' => 'nullable|numeric|min:0|max:100',
            'motivo_descuento' => 'nullable|string',
            'autorizacion_descuento' => 'nullable|string',
            'importe_final' => 'required|numeric|min:0',
        ]);

        $horario = HorarioCurso::findOrFail($validated['horario_curso_id']);

        // Regla RN-CAJ-07: Validar que el horario no supere su capacidad
        if (!$horario->tieneCupo()) {
            return back()->withInput()->with('error', 'El horario seleccionado ya alcanzó su capacidad máxima de cupos.');
        }

        $tarifa = Tarifa::with('modalidad')->findOrFail($validated['tarifa_id']);

        // Calcular fecha fin (1 mes / 30 días calendario por defecto)
        $fechaInicio = \Carbon\Carbon::parse($validated['fecha_inicio']);
        $fechaFin = (clone $fechaInicio)->addDays(30);

        return DB::transaction(function() use ($validated, $horario, $tarifa, $fechaInicio, $fechaFin) {
            // 1. Crear Inscripción
            $inscripcion = Inscripcion::create([
                'codigo_inscripcion' => Inscripcion::generarCodigo(),
                'persona_id' => $validated['persona_id'],
                'horario_curso_id' => $horario->id,
                'tarifa_id' => $tarifa->id,
                'fecha_inscripcion' => now()->toDateString(),
                'fecha_inicio' => $fechaInicio->toDateString(),
                'fecha_fin' => $fechaFin->toDateString(),
                'estado' => 'ACTIVA',
                'usuario_creador_id' => auth()->id()
            ]);

            // 2. Registrar Pago y Facturación
            $descuentoPorcentaje = $validated['descuento_porcentaje'] ?? 0;
            $montoDescuento = ($validated['importe_base'] * ($descuentoPorcentaje / 100));

            PagoFactura::create([
                'inscripcion_id' => $inscripcion->id,
                'numero_factura' => $validated['numero_factura'],
                'importe_base' => $validated['importe_base'],
                'porcentaje_descuento' => $descuentoPorcentaje,
                'monto_descuento' => $montoDescuento,
                'importe_final' => $validated['importe_final'],
                'tipo_beneficio' => $validated['tipo_beneficio'] ?? 'NINGUNO',
                'motivo_descuento' => $validated['motivo_descuento'] ?? null,
                'autorizacion_descuento' => $validated['autorizacion_descuento'] ?? null,
                'fecha_pago' => now(),
                'cajero_id' => auth()->id()
            ]);

            // 3. Generar Tarjeta con Código QR único
            $uuid = (string) Str::uuid();
            $datosQr = json_encode([
                'sis' => 'SISCOE-COE',
                'uuid' => $uuid,
                'ins' => $inscripcion->codigo_inscripcion,
                'fac' => $validated['numero_factura'],
                'vig' => $fechaFin->format('Y-m-d')
            ]);

            $clasesPermitidas = $tarifa->modalidad->cantidad_clases ?? 16;

            TarjetaInscripcion::create([
                'inscripcion_id' => $inscripcion->id,
                'codigo_tarjeta' => $uuid,
                'codigo_qr' => $datosQr,
                'fecha_emision' => now()->toDateString(),
                'fecha_vencimiento' => $fechaFin->toDateString(),
                'asistencias_permitidas' => $clasesPermitidas,
                'asistencias_utilizadas' => 0,
                'estado' => 'HABILITADA'
            ]);

            return redirect()->route('cajas.tarjeta', $inscripcion->id)
                ->with('success', "Inscripción {$inscripcion->codigo_inscripcion} realizada exitosamente.");
        });
    }

    /**
     * Vista de Impresión de la Tarjeta (Cara 1 y Cara 2 idéntico al Excel + QR)
     */
    public function imprimirTarjeta($id)
    {
        $inscripcion = Inscripcion::with([
            'persona',
            'horarioCurso.disciplina',
            'horarioCurso.instructor.persona',
            'tarifa.modalidad',
            'pago',
            'tarjeta'
        ])->findOrFail($id);

        // Generar código QR en SVG vectorial
        $qrCodeSvg = QrCode::size(130)
            ->color(15, 23, 42)
            ->generate($inscripcion->tarjeta->codigo_qr ?? $inscripcion->codigo_inscripcion);

        return view('cajas.tarjeta', compact('inscripcion', 'qrCodeSvg'));
    }

    /**
     * Historial general de inscripciones y facturas
     */
    public function historial(Request $request)
    {
        $query = Inscripcion::with(['persona', 'horarioCurso.disciplina', 'pago', 'tarjeta']);

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where(function($q) use ($b) {
                $q->where('codigo_inscripcion', 'ilike', "%{$b}%")
                  ->orWhereHas('pago', fn($p) => $p->where('numero_factura', 'ilike', "%{$b}%"))
                  ->orWhereHas('persona', fn($p) => $p->where('nombres', 'ilike', "%{$b}%")
                        ->orWhere('paterno', 'ilike', "%{$b}%")
                        ->orWhere('ci', 'ilike', "%{$b}%"));
            });
        }

        if ($request->filled('disciplina_id')) {
            $query->whereHas('horarioCurso', fn($h) => $h->where('disciplina_id', $request->disciplina_id));
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $inscripciones = $query->latest()->paginate(15)->withQueryString();
        $disciplinas = Disciplina::where('activo', true)->get();

        return view('cajas.historial', compact('inscripciones', 'disciplinas'));
    }

    /**
     * Tablero de Horarios y Cupos en Vivo
     */
    public function horarios()
    {
        $disciplinas = Disciplina::where('activo', true)
            ->with(['horarios' => function($q) {
                $q->with(['instructor.persona', 'modalidad'])
                  ->withCount(['inscripcionesActivas']);
            }])
            ->get();

        return view('cajas.horarios', compact('disciplinas'));
    }

    /**
     * Pantalla de Reprogramaciones (RF-CAJ-49 a 53)
     */
    public function reprogramaciones(Request $request)
    {
        $reprogramaciones = Reprogramacion::with(['inscripcion.persona', 'inscripcion.horarioCurso.disciplina', 'usuario'])
            ->latest()
            ->paginate(15);

        return view('cajas.reprogramaciones', compact('reprogramaciones'));
    }

    /**
     * Guardar una reprogramación autorizada
     */
    public function guardarReprogramacion(Request $request)
    {
        $validated = $request->validate([
            'inscripcion_id' => 'required|exists:inscripciones,id',
            'nueva_fecha_vencimiento' => 'required|date|after:today',
            'clases_reprogramadas' => 'required|integer|min:1',
            'motivo' => 'required|string|max:500',
            'hoja_ruta' => 'required|string|max:100',
            'autorizado_por' => 'required|string|max:150',
        ]);

        return DB::transaction(function() use ($validated) {
            $inscripcion = Inscripcion::findOrFail($validated['inscripcion_id']);
            $fechaAnterior = $inscripcion->fecha_fin;

            // Registrar Reprogramación en auditoría
            Reprogramacion::create([
                'inscripcion_id' => $inscripcion->id,
                'fecha_anterior_vencimiento' => $fechaAnterior,
                'nueva_fecha_vencimiento' => $validated['nueva_fecha_vencimiento'],
                'clases_reprogramadas' => $validated['clases_reprogramadas'],
                'motivo' => $validated['motivo'],
                'hoja_ruta' => $validated['hoja_ruta'],
                'autorizado_por' => $validated['autorizado_por'],
                'usuario_id' => auth()->id()
            ]);

            // Actualizar fecha en inscripción y tarjeta
            $inscripcion->update([
                'fecha_fin' => $validated['nueva_fecha_vencimiento'],
                'estado' => 'ACTIVA'
            ]);

            if ($inscripcion->tarjeta) {
                $inscripcion->tarjeta->update([
                    'fecha_vencimiento' => $validated['nueva_fecha_vencimiento'],
                    'estado' => 'HABILITADA'
                ]);
            }

            return redirect()->route('cajas.reprogramaciones')
                ->with('success', "Inscripción {$inscripcion->codigo_inscripcion} reprogramada con éxito hasta el {$validated['nueva_fecha_vencimiento']}.");
        });
    }

    /**
     * Arqueo de Turno y Reportes
     */
    public function reportes(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth()->toDateString());
        $fechaFin = $request->get('fecha_fin', now()->toDateString());

        // Recaudación agrupada por Disciplina
        $ingresosPorDisciplina = DB::table('pagos_facturas')
            ->join('inscripciones', 'pagos_facturas.inscripcion_id', '=', 'inscripciones.id')
            ->join('horarios_cursos', 'inscripciones.horario_curso_id', '=', 'horarios_cursos.id')
            ->join('disciplinas', 'horarios_cursos.disciplina_id', '=', 'disciplinas.id')
            ->whereBetween('pagos_facturas.fecha_pago', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->select('disciplinas.nombre', DB::raw('SUM(pagos_facturas.importe_final) as total'), DB::raw('COUNT(inscripciones.id) as cantidad'))
            ->groupBy('disciplinas.nombre')
            ->get();

        $totalRecaudadoPeriodo = $ingresosPorDisciplina->sum('total');
        $totalInscripcionesPeriodo = $ingresosPorDisciplina->sum('cantidad');

        return view('cajas.reportes', compact(
            'ingresosPorDisciplina',
            'totalRecaudadoPeriodo',
            'totalInscripcionesPeriodo',
            'fechaInicio',
            'fechaFin'
        ));
    }
}
