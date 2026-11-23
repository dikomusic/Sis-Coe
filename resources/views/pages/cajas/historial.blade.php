@extends('templates.erp-layout', ['title' => 'Historial de Cobros'])

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Historial General de Cobros</h2>
            <p class="text-xs text-slate-500 mt-0.5">Búsqueda y consulta de inscripciones emitidas, facturas y tarjetas COE.</p>
        </div>

        <a href="{{ route('cajas.ventanilla') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#FF0066] hover:bg-[#E6005C] text-white font-bold text-xs shadow-md shadow-[#FF0066]/30 transition-all">
            + Nueva Inscripción
        </a>
    </div>

    <!-- Filtros y Buscador -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <form method="GET" action="{{ route('cajas.historial') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2">
                <input 
                    type="text" 
                    name="buscar" 
                    value="{{ $filtro ?? '' }}" 
                    placeholder="Buscar por CI, Nombre, Código o N° Factura..."
                    class="block w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-blue-600 focus:ring-blue-600"
                >
            </div>
            <div>
                <select name="estado" class="block w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:border-blue-600 focus:ring-blue-600">
                    <option value="">Todos los Estados</option>
                    <option value="ACTIVA" @selected(($estado ?? '') === 'ACTIVA')>Activas</option>
                    <option value="CONCLUIDA" @selected(($estado ?? '') === 'CONCLUIDA')>Concluidas</option>
                    <option value="CANCELADA" @selected(($estado ?? '') === 'CANCELADA')>Canceladas</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 px-4 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold transition-colors">
                    Filtrar
                </button>
                @if(!empty($filtro) || !empty($estado))
                    <a href="{{ route('cajas.historial') }}" class="py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition-colors">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabla de Resultados -->
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-slate-800/50 text-[11px] uppercase tracking-wider font-bold text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-6 py-3.5">Código / Fecha</th>
                        <th class="px-6 py-3.5">Alumno</th>
                        <th class="px-6 py-3.5">Disciplina & Horario</th>
                        <th class="px-6 py-3.5">Factura / Recibo</th>
                        <th class="px-6 py-3.5">Importe</th>
                        <th class="px-6 py-3.5">Estado</th>
                        <th class="px-6 py-3.5 text-right">Tarjeta</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($inscripciones as $inscripcion)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-slate-900 dark:text-white">{{ $inscripcion->codigo_inscripcion }}</span>
                                <span class="block text-[11px] text-slate-400">{{ $inscripcion->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-800 dark:text-white block">{{ $inscripcion->persona->nombre_completo }}</span>
                                <span class="text-xs text-slate-500">CI: {{ $inscripcion->persona->ci_completo }} • <span class="uppercase text-[10px] font-bold text-blue-600">{{ $inscripcion->persona->tipo_persona }}</span></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-700 dark:text-slate-200 block">{{ $inscripcion->horarioCurso?->disciplina?->nombre ?? 'N/A' }}</span>
                                <span class="text-xs text-slate-500">{{ $inscripcion->horarioCurso?->dias_semana }} ({{ substr($inscripcion->horarioCurso?->hora_inicio ?? '', 0, 5) }}-{{ substr($inscripcion->horarioCurso?->hora_fin ?? '', 0, 5) }})</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $inscripcion->pago?->numero_factura ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-[#FF0066]">Bs. {{ number_format($inscripcion->pago?->importe_final ?? 0, 2) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-[11px] font-bold rounded-full uppercase {{ $inscripcion->estado === 'ACTIVA' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $inscripcion->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($inscripcion->tarjeta)
                                    <a href="{{ route('cajas.tarjeta', $inscripcion->tarjeta->id) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold bg-[#FF0066]/10 text-[#FF0066] hover:bg-[#FF0066]/20 transition-colors">
                                        <span>Ver Tarjeta</span>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-400">
                                No se encontraron inscripciones con los filtros especificados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($inscripciones->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                {{ $inscripciones->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
