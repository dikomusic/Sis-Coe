@extends('templates.erp-layout', ['title' => 'Reprogramaciones'])

@section('content')
<div class="space-y-6" x-data="{ modalReprogramar: false, tarjetaId: '', codigoTarjeta: '' }">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Reprogramación de Tarjetas</h2>
            <p class="text-xs text-slate-500 mt-0.5">Gestión de extensiones de vigencia por motivo de salud, viaje u orden institucional.</p>
        </div>
    </div>

    <!-- Tabla de Reprogramaciones Recientes -->
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="font-bold text-slate-800 dark:text-white text-base">Historial de Reprogramaciones Autorizadas</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-slate-800/50 text-[11px] uppercase tracking-wider font-bold text-slate-500 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-6 py-3.5">Fecha Reg.</th>
                        <th class="px-6 py-3.5">Alumno</th>
                        <th class="px-6 py-3.5">Disciplina</th>
                        <th class="px-6 py-3.5">Vencimiento Anterior</th>
                        <th class="px-6 py-3.5">Nuevo Vencimiento</th>
                        <th class="px-6 py-3.5">Motivo / Autorizado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($reprogramaciones as $rep)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40">
                            <td class="px-6 py-4 text-xs font-mono text-slate-500">
                                {{ $rep->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-800 dark:text-white block">{{ $rep->inscripcion?->persona?->nombre_completo ?? 'N/A' }}</span>
                                <span class="text-xs text-slate-500">CI: {{ $rep->inscripcion?->persona?->ci_completo }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-slate-700 dark:text-slate-300">
                                {{ $rep->inscripcion?->horarioCurso?->disciplina?->nombre ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-rose-600 font-semibold">
                                {{ $rep->fecha_anterior_vencimiento ? $rep->fecha_anterior_vencimiento->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-emerald-600 font-bold">
                                {{ $rep->nueva_fecha_vencimiento ? $rep->nueva_fecha_vencimiento->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-600 dark:text-slate-400">
                                <p class="font-medium">{{ $rep->motivo }}</p>
                                <span class="text-[10px] text-slate-400">Por: <strong>{{ $rep->autorizado_por }}</strong></span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                                No se registran reprogramaciones hasta el momento.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reprogramaciones->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                {{ $reprogramaciones->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
