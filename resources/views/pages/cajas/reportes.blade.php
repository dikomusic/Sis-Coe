@extends('templates.erp-layout', ['title' => 'Arqueo y Reportes de Caja'])

@section('content')
<div class="space-y-6">
    <!-- Encabezado y Filtros Rápidos -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-5">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider rounded bg-[#FF0066]/10 text-[#FF0066] border border-[#FF0066]/30">Auditoría Financiera</span>
                <span class="text-xs text-slate-400 font-bold">• Ventanilla de Caja COE</span>
            </div>
            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight mt-1">Arqueo y Reporte Financiero</h2>
            <div class="flex items-center gap-2 mt-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-black {{ $filtro === 'todo' ? 'bg-[#FF0066] text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ $filtro === 'todo' ? 'bg-[#EDD00A]' : 'bg-emerald-500' }}"></span>
                    {{ $etiquetaPeriodo }}
                </span>
                <span class="text-xs text-slate-500">Conciliación de comprobantes, desglose de disciplinas y flujo de ventanilla.</span>
            </div>
        </div>

        <!-- Barra de Acciones y Filtros de Período -->
        <div class="flex flex-wrap items-center gap-2 no-print">
            <!-- Píldoras de Período Rápido -->
            <div class="inline-flex rounded-xl bg-slate-100 dark:bg-slate-800/80 p-1 border border-slate-200 dark:border-slate-700">
                <a href="{{ route('cajas.reportes', ['filtro' => 'todo']) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-black transition-all inline-flex items-center gap-1.5 {{ $filtro === 'todo' ? 'bg-[#FF0066] text-white shadow' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Histórico Total</span>
                </a>
                <a href="{{ route('cajas.reportes', ['filtro' => 'mes', 'fecha' => $fecha]) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-black transition-all inline-flex items-center gap-1.5 {{ $filtro === 'mes' ? 'bg-[#FF0066] text-white shadow' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Este Mes</span>
                </a>
                <a href="{{ route('cajas.reportes', ['filtro' => 'dia', 'fecha' => now()->toDateString()]) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-black transition-all inline-flex items-center gap-1.5 {{ $filtro === 'dia' && $fecha === now()->toDateString() ? 'bg-[#FF0066] text-white shadow' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Cierre Hoy</span>
                </a>
            </div>

            <!-- Selector de Fecha Puntual -->
            <form method="GET" action="{{ route('cajas.reportes') }}" class="flex items-center gap-2">
                <input type="hidden" name="filtro" value="dia">
                @if($disciplinaFiltro)
                    <input type="hidden" name="disciplina" value="{{ $disciplinaFiltro }}">
                @endif
                <input 
                    type="date" 
                    name="fecha" 
                    value="{{ $fecha }}" 
                    onchange="this.form.submit()"
                    title="Seleccionar fecha para cierre diario"
                    class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-1.5 text-xs font-bold text-slate-800 dark:text-slate-100 shadow-sm focus:border-[#FF0066] focus:ring-[#FF0066]"
                >
            </form>

            <!-- Filtro Desplegable de Disciplina -->
            <form method="GET" action="{{ route('cajas.reportes') }}" class="flex items-center">
                <input type="hidden" name="filtro" value="{{ $filtro }}">
                <input type="hidden" name="fecha" value="{{ $fecha }}">
                <select 
                    name="disciplina" 
                    onchange="this.form.submit()" 
                    class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-1.5 text-xs font-bold text-slate-800 dark:text-slate-100 shadow-sm focus:border-[#FF0066] focus:ring-[#FF0066]"
                >
                    <option value="">Todas las Disciplinas</option>
                    @foreach($disciplinas as $d)
                        <option value="{{ $d->nombre }}" {{ $disciplinaFiltro === $d->nombre ? 'selected' : '' }}>
                            {{ $d->nombre }}
                        </option>
                    @endforeach
                </select>
            </form>

            <!-- Botón Imprimir Acta -->
            <button 
                type="button" 
                onclick="window.print()" 
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs border border-slate-700 shadow-sm transition-all active:scale-[0.98]"
            >
                <svg class="h-4 w-4 text-[#EDD00A]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Imprimir Reporte</span>
            </button>
        </div>
    </div>

    @if($totalInscripciones == 0)
        <!-- Aviso Inteligente de Período sin Movimientos -->
        <div class="rounded-2xl border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-950/40 p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-amber-900 dark:text-amber-200 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-xl bg-amber-200 dark:bg-amber-900 text-amber-800 dark:text-amber-200 flex items-center justify-center shrink-0">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="font-bold">No se registraron cobros en el período actual: {{ $etiquetaPeriodo }}.</p>
                    <p class="text-amber-700 dark:text-amber-400 mt-0.5">La base de datos contiene más de 2,280 cobros registrados en el histórico. Puede alternar a ver el Histórico Total o cambiar la fecha en la barra superior.</p>
                </div>
            </div>
            <a href="{{ route('cajas.reportes', ['filtro' => 'todo']) }}" class="px-4 py-2 rounded-xl bg-[#FF0066] hover:bg-[#E6005C] text-white font-bold text-xs shadow-md shadow-[#FF0066]/20 transition-all shrink-0 text-center">
                Ver Histórico Total Consolidado
            </a>
        </div>
    @endif

    <!-- Tarjetas de Resumen / KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Recaudado -->
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Recaudado</span>
                <span class="h-8 w-8 rounded-lg bg-[#FF0066]/10 text-[#FF0066] flex items-center justify-center font-bold text-xs">Bs</span>
            </div>
            <p class="text-3xl font-black text-[#FF0066] mt-2">Bs. {{ number_format($totalRecaudado, 2) }}</p>
            <span class="text-[11px] text-slate-500 mt-1 block">
                @if($filtro === 'todo')
                    Padrón consolidado histórico
                @elseif($filtro === 'mes')
                    Recaudación del mes en curso
                @else
                    Fecha: {{ date('d/m/Y', strtotime($fecha)) }}
                @endif
            </span>
        </div>

        <!-- Total Alumnos / Pases -->
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Inscripciones / Cobros</span>
                <span class="h-8 w-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 flex items-center justify-center font-bold text-xs">#</span>
            </div>
            <p class="text-3xl font-black text-slate-800 dark:text-white mt-2">{{ number_format($totalInscripciones) }}</p>
            <span class="text-[11px] text-slate-500 mt-1 block">Comprobantes procesados</span>
        </div>

        <!-- Descuentos Aplicados -->
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Descuentos Concedidos</span>
                <span class="h-8 w-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 flex items-center justify-center font-bold text-xs">%</span>
            </div>
            <p class="text-3xl font-black text-emerald-600 mt-2">Bs. {{ number_format($totalDescuentos, 2) }}</p>
            <span class="text-[11px] text-slate-500 mt-1 block">Convenios y becas aplicados</span>
        </div>

        <!-- Ticket Promedio -->
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Ticket Promedio</span>
                <span class="h-8 w-8 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 flex items-center justify-center font-bold text-xs">x̄</span>
            </div>
            @php
                $promedio = $totalInscripciones > 0 ? ($totalRecaudado / $totalInscripciones) : 0;
            @endphp
            <p class="text-3xl font-black text-slate-800 dark:text-white mt-2">Bs. {{ number_format($promedio, 2) }}</p>
            <span class="text-[11px] text-slate-500 mt-1 block">Promedio por comprobante</span>
        </div>
    </div>

    <!-- Sección de Gráficos Analíticos -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 no-print">
        <!-- Gráfico 1: Dona - Distribución por Disciplina -->
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3 border-b border-slate-100 dark:border-slate-800 pb-2">
                <h3 class="font-bold text-slate-800 dark:text-white text-sm">Ingresos por Disciplina</h3>
                <span class="text-[10px] text-slate-400 font-bold uppercase">Distribución</span>
            </div>
            <div class="relative h-64 flex items-center justify-center">
                @if(count($porDisciplina) > 0)
                    <canvas id="chartDisciplinas"></canvas>
                @else
                    <p class="text-xs text-slate-400 text-center">Sin datos en este período.</p>
                @endif
            </div>
        </div>

        <!-- Gráfico 2: Barras - Ingresos por Categoría de Alumno -->
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3 border-b border-slate-100 dark:border-slate-800 pb-2">
                <h3 class="font-bold text-slate-800 dark:text-white text-sm">Ingresos por Categoría</h3>
                <span class="text-[10px] text-slate-400 font-bold uppercase">Socio vs Particular</span>
            </div>
            <div class="relative h-64 flex items-center justify-center">
                @if(count($porTipoPersona) > 0)
                    <canvas id="chartCategorias"></canvas>
                @else
                    <p class="text-xs text-slate-400 text-center">Sin datos en este período.</p>
                @endif
            </div>
        </div>

        <!-- Gráfico 3: Línea - Tendencia de Cobros -->
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3 border-b border-slate-100 dark:border-slate-800 pb-2">
                <h3 class="font-bold text-slate-800 dark:text-white text-sm">
                    {{ $filtro === 'todo' ? 'Evolución Mensual' : 'Tendencia de Cobros' }}
                </h3>
                <span class="text-[10px] text-slate-400 font-bold uppercase">Cronología</span>
            </div>
            <div class="relative h-64 flex items-center justify-center">
                @if(count($tendencia) > 0)
                    <canvas id="chartSemanal"></canvas>
                @else
                    <p class="text-xs text-slate-400 text-center">Sin datos en este período.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Tabla Detallada de Facturas / Comprobantes Emitidos -->
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="font-bold text-slate-800 dark:text-white text-base">Comprobantes y Facturas Emitidas</h3>
                <p class="text-xs text-slate-400 mt-0.5">
                    @if($totalInscripciones > count($comprobantesPaginados))
                        Mostrando los {{ count($comprobantesPaginados) }} comprobantes más recientes de {{ number_format($totalInscripciones) }} totales en este filtro.
                    @else
                        Detalle individual para auditoría y balance de caja física ({{ $totalInscripciones }} registros).
                    @endif
                </p>
            </div>
            <span class="px-3 py-1 text-xs font-black rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 self-start sm:self-auto">
                Bs. {{ number_format($totalRecaudado, 2) }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-slate-800/50 text-[11px] uppercase tracking-wider font-bold text-slate-500 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-6 py-3.5">N° Recibo / Factura</th>
                        <th class="px-6 py-3.5">Fecha / Hora</th>
                        <th class="px-6 py-3.5">Alumno</th>
                        <th class="px-6 py-3.5">Categoría</th>
                        <th class="px-6 py-3.5">Disciplina & Turno</th>
                        <th class="px-6 py-3.5 text-right">Base</th>
                        <th class="px-6 py-3.5 text-right">Desc.</th>
                        <th class="px-6 py-3.5 text-right">Total Pagado</th>
                        <th class="px-6 py-3.5 text-right no-print">Tarjeta</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($comprobantesPaginados as $pago)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40">
                            <td class="px-6 py-3.5 font-mono font-bold text-slate-900 dark:text-white text-xs">
                                {{ $pago->numero_factura }}
                            </td>
                            <td class="px-6 py-3.5 font-mono text-xs text-slate-500">
                                {{ $pago->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-3.5 font-bold text-slate-800 dark:text-white">
                                {{ $pago->inscripcion?->persona?->nombre_completo ?? 'N/A' }}
                                <span class="block text-[11px] font-normal text-slate-400">CI: {{ $pago->inscripcion?->persona?->ci_completo }}</span>
                            </td>
                            <td class="px-6 py-3.5">
                                @php
                                    $tipo = $pago->inscripcion?->persona?->tipo_persona ?? 'PARTICULAR';
                                @endphp
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full uppercase
                                    {{ $tipo === 'SOCIO' ? 'bg-[#FF0066]/10 text-[#FF0066] border border-[#FF0066]/30' : ($tipo === 'BENEFICIARIO' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700') }}">
                                    {{ $tipo }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-xs text-slate-700 dark:text-slate-300">
                                <strong>{{ $pago->inscripcion?->horarioCurso?->disciplina?->nombre ?? 'N/A' }}</strong>
                                <span class="block text-[11px] text-slate-400">
                                    {{ $pago->inscripcion?->horarioCurso?->dias_semana ?? 'Lun a Vie' }} 
                                    ({{ substr($pago->inscripcion?->horarioCurso?->hora_inicio ?? '', 0, 5) }}-{{ substr($pago->inscripcion?->horarioCurso?->hora_fin ?? '', 0, 5) }})
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-right font-mono text-xs text-slate-500">
                                Bs. {{ number_format($pago->importe_base, 2) }}
                            </td>
                            <td class="px-6 py-3.5 text-right font-mono text-xs text-emerald-600 font-semibold">
                                {{ $pago->monto_descuento > 0 ? ('-Bs. ' . number_format($pago->monto_descuento, 2)) : '-' }}
                            </td>
                            <td class="px-6 py-3.5 text-right font-mono font-black text-sm text-[#FF0066]">
                                Bs. {{ number_format($pago->importe_final, 2) }}
                            </td>
                            <td class="px-6 py-3.5 text-right no-print">
                                @if($pago->inscripcion?->tarjeta)
                                    <a href="{{ route('cajas.tarjeta', $pago->inscripcion->tarjeta->id) }}" 
                                       target="_blank"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-[#FF0066]/10 text-[#FF0066] hover:bg-[#FF0066]/20 transition-colors">
                                        <span>Ver QR</span>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-slate-400 text-xs">
                                No se encontraron registros para el filtro seleccionado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Sección Especial para Impresión Oficial de Acta de Arqueo -->
    <div class="hidden print:block pt-8 space-y-6">
        <div class="border-t-2 border-dashed border-slate-400 pt-6">
            <div class="text-center mb-4">
                <h3 class="text-base font-black uppercase tracking-wider">CÍRCULO DE OFICIALES DEL EJÉRCITO</h3>
                <p class="text-xs font-bold uppercase text-slate-600">Dirección de Deportes y Actividades Recreativas • Ventanilla de Caja</p>
                <h4 class="text-sm font-black uppercase text-slate-900 mt-2">Acta Oficial de Arqueo y Cierre Financiero</h4>
                <p class="text-xs text-slate-600 mt-0.5">Período: {{ $etiquetaPeriodo }}</p>
            </div>

            <div class="grid grid-cols-3 gap-4 border border-black p-4 text-xs my-4">
                <div>
                    <span class="block font-bold">TOTAL RECAUDADO:</span>
                    <span class="text-sm font-black">Bs. {{ number_format($totalRecaudado, 2) }}</span>
                </div>
                <div>
                    <span class="block font-bold">TOTAL COMPROBANTES:</span>
                    <span class="text-sm font-black">{{ number_format($totalInscripciones) }} pases</span>
                </div>
                <div>
                    <span class="block font-bold">DESCUENTOS / BECAS:</span>
                    <span class="text-sm font-black">Bs. {{ number_format($totalDescuentos, 2) }}</span>
                </div>
            </div>

            <!-- Desglose por Disciplina para Acta -->
            <div class="my-4">
                <p class="text-xs font-bold uppercase border-b border-black pb-1 mb-2">Desglose de Ingresos por Disciplina:</p>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    @foreach($porDisciplina as $nombreDisc => $dataDisc)
                        <div class="flex justify-between border-b border-slate-300 py-0.5">
                            <span>{{ $nombreDisc }} ({{ $dataDisc['cantidad'] }}):</span>
                            <span class="font-bold">Bs. {{ number_format($dataDisc['total'], 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-12 pt-16 text-center text-xs">
                <div class="border-t border-black pt-2">
                    <p class="font-bold uppercase">{{ auth()->user()->name ?? 'Cajero de Turno' }}</p>
                    <p class="text-slate-500">Cajero Responsable de Ventanilla COE</p>
                </div>
                <div class="border-t border-black pt-2">
                    <p class="font-bold uppercase">Tesorería / Administración</p>
                    <p class="text-slate-500">V° B° Recibido Conforme COE</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Carga de Chart.js para Renderizado de Gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const paletaCOE = [
        '#FF0066', '#EDD00A', '#3B82F6', '#10B981', 
        '#8B5CF6', '#F97316', '#06B6D4', '#64748B',
        '#EC4899', '#14B8A6', '#F43F5E', '#84CC16'
    ];

    // 1. Gráfico de Disciplinas (Dona)
    @if(count($porDisciplina) > 0)
    const ctxDisc = document.getElementById('chartDisciplinas');
    if (ctxDisc) {
        new Chart(ctxDisc, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($porDisciplina->toArray())) !!},
                datasets: [{
                    data: {!! json_encode(array_column($porDisciplina->toArray(), 'total')) !!},
                    backgroundColor: paletaCOE,
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { boxWidth: 10, font: { size: 9, weight: 'bold' } }
                    }
                }
            }
        });
    }
    @endif

    // 2. Gráfico de Categorías (Barras)
    @if(count($porTipoPersona) > 0)
    const ctxCat = document.getElementById('chartCategorias');
    if (ctxCat) {
        new Chart(ctxCat, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($porTipoPersona->toArray())) !!},
                datasets: [{
                    label: 'Recaudado (Bs.)',
                    data: {!! json_encode(array_column($porTipoPersona->toArray(), 'total')) !!},
                    backgroundColor: ['#FF0066', '#EDD00A', '#3B82F6'],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                    x: { grid: { display: false } }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
    @endif

    // 3. Gráfico de Tendencia (Línea)
    @if(count($tendencia) > 0)
    const ctxSem = document.getElementById('chartSemanal');
    if (ctxSem) {
        new Chart(ctxSem, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($tendencia, 'dia')) !!},
                datasets: [{
                    label: 'Total (Bs.)',
                    data: {!! json_encode(array_column($tendencia, 'total')) !!},
                    borderColor: '#FF0066',
                    backgroundColor: 'rgba(255, 0, 102, 0.1)',
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#FF0066',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                    x: { grid: { display: false } }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
    @endif
});
</script>
@endsection
