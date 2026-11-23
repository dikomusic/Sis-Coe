@extends('templates.erp-layout', ['title' => 'Expediente Único del Alumno'])

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb y Acciones Superiores -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400">
                <a href="{{ route('cajas.alumnos') }}" class="hover:text-[#FF0066] transition-colors flex items-center gap-1">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span>Padrón de Alumnos</span>
                </a>
                <span>/</span>
                <span class="text-slate-600 dark:text-slate-300 font-bold">Expediente Único</span>
            </div>
            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight mt-1">
                Expediente e Historial del Alumno
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Consolidación completa de todas las compras, mensualidades y asistencias de esta persona.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('cajas.ventanilla', ['search' => $alumno->ci]) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#FF0066] hover:bg-[#E6005C] text-white font-black text-xs shadow-md shadow-[#FF0066]/25 transition-all active:scale-95">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span>Cobrar / Renovar en Ventanilla</span>
            </a>
        </div>
    </div>

    <!-- Tarjeta de Perfil de la Persona Única (Atomic Design: Organism) -->
    <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="h-16 w-16 rounded-2xl bg-gradient-to-tr from-[#FF0066] to-[#C91459] text-white font-black text-2xl flex items-center justify-center shadow-lg shadow-[#FF0066]/20 shrink-0">
                    {{ strtoupper(substr($alumno->nombres ?? 'A', 0, 1)) }}
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-xl font-black text-slate-900 dark:text-white">
                            {{ $alumno->nombre_completo }}
                        </h3>
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-black uppercase
                            {{ $alumno->tipo_persona === 'SOCIO' || $alumno->tipo_persona === 'SOCIO_TITULAR' ? 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300' : '' }}
                            {{ $alumno->tipo_persona === 'BENEFICIARIO' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : '' }}
                            {{ $alumno->tipo_persona === 'PARTICULAR' ? 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300' : '' }}">
                            {{ $alumno->tipo_persona ?? 'PARTICULAR' }}
                        </span>
                    </div>

                    <div class="flex items-center gap-3 text-xs text-slate-500 flex-wrap">
                        <span class="inline-flex items-center gap-1 font-mono font-bold text-slate-700 dark:text-slate-300">
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>
                            CI: {{ $alumno->ci_completo }}
                        </span>

                        @if($alumno->telf_cel)
                            <span class="inline-flex items-center gap-1">
                                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                Celular: <strong class="text-slate-700 dark:text-slate-300">{{ $alumno->telf_cel }}</strong>
                            </span>
                        @endif

                        @if($alumno->direccion)
                            <span class="inline-flex items-center gap-1">
                                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $alumno->direccion }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Identificador único en el sistema -->
            <div class="bg-slate-50 dark:bg-slate-800/60 p-3 rounded-xl border border-slate-100 dark:border-slate-800 text-xs shrink-0 self-start md:self-auto">
                <span class="text-[10px] uppercase font-bold text-slate-400 block">ID de Padrón Único</span>
                <span class="font-mono font-black text-slate-800 dark:text-slate-200">COE-ALUM-{{ str_pad($alumno->id, 5, '0', STR_PAD_LEFT) }}</span>
                <span class="text-[10px] text-emerald-600 dark:text-emerald-400 block font-semibold mt-0.5">Perfil Activo</span>
            </div>
        </div>
    </div>

    <!-- KPIs del Expediente del Alumno -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- 1. Total Cursos / Renovaciones -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-[#FF0066]/10 text-[#FF0066] flex items-center justify-center shrink-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Inscripciones / Pagos</span>
                <span class="text-xl font-black text-slate-900 dark:text-white">{{ $totalInscripciones }}</span>
            </div>
        </div>

        <!-- 2. Inversión Total Acumulada -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Total Invertido</span>
                <span class="text-lg font-black text-emerald-600 dark:text-emerald-400">Bs. {{ number_format($totalInvertido, 2) }}</span>
            </div>
        </div>

        <!-- 3. Disciplinas Cursadas -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Disciplinas</span>
                <span class="text-sm font-black text-slate-900 dark:text-white truncate block">
                    {{ $disciplinasTomadas->join(', ') ?: 'Ninguna' }}
                </span>
            </div>
        </div>

        <!-- 4. Antigüedad / Primer Registro -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Primer Registro</span>
                <span class="text-xs font-black text-slate-800 dark:text-slate-200">
                    {{ $primeraInscripcion?->created_at ? $primeraInscripcion->created_at->format('d/m/Y') : 'Reciente' }}
                </span>
            </div>
        </div>
    </div>

    <!-- HISTORIAL CRONOLÓGICO DE COMPRAS E INSCRIPCIONES -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <div>
                <h4 class="font-black text-base text-slate-900 dark:text-white">
                    Historial Cronológico de Compras & Inscripciones
                </h4>
                <p class="text-xs text-slate-500 mt-0.5">
                    Detalle de todos los comprobantes, turnos cursados, instructores y tarjetas QR generadas.
                </p>
            </div>
            <span class="px-2.5 py-1 rounded-full text-xs font-black bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                {{ $totalInscripciones }} transacciones
            </span>
        </div>

        <!-- MÓVIL: CARDS DE HISTORIAL (< 768px) -->
        <div class="block md:hidden divide-y divide-slate-100 dark:divide-slate-800 p-3 space-y-3">
            @forelse($inscripciones as $ins)
                <div class="bg-slate-50 dark:bg-slate-800/50 p-3.5 rounded-xl space-y-2.5">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <span class="text-xs font-black text-[#FF0066]">
                                {{ $ins->horarioCurso?->disciplina?->nombre ?? 'CURSO COE' }}
                            </span>
                            <span class="text-[11px] text-slate-500 block">
                                {{ $ins->horarioCurso?->modalidad?->nombre ?? 'Regular' }}
                            </span>
                        </div>
                        <span class="font-mono font-black text-sm text-emerald-600 dark:text-emerald-400">
                            Bs. {{ number_format((float) ($ins->pago?->importe_final ?? 0), 2) }}
                        </span>
                    </div>

                    <div class="text-xs text-slate-600 dark:text-slate-300 space-y-1">
                        <p>
                            Horario: <strong class="font-mono text-slate-900 dark:text-white">
                                {{ substr($ins->horarioCurso?->hora_inicio ?? '00:00', 0, 5) }} - {{ substr($ins->horarioCurso?->hora_fin ?? '00:00', 0, 5) }}
                            </strong> • {{ $ins->horarioCurso?->dias_semana ?? 'Lun a Vie' }}
                        </p>
                        <p>
                            Docente: <strong class="text-slate-800 dark:text-slate-200">
                                {{ $ins->horarioCurso?->instructor?->persona?->nombre_completo ?? 'Sin Asignar' }}
                            </strong>
                        </p>
                        <p class="text-[11px] text-slate-400">
                            Comprobante: <span class="font-mono font-bold text-slate-700 dark:text-slate-300">{{ $ins->pago?->numero_factura ?? 'REC-S/N' }}</span> • 
                            Fecha: {{ $ins->fecha_inscripcion ? $ins->fecha_inscripcion->format('d/m/Y') : ($ins->created_at ? $ins->created_at->format('d/m/Y') : '-') }}
                        </p>
                    </div>

                    <div class="pt-2 border-t border-slate-200/60 dark:border-slate-700 flex items-center justify-between">
                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase
                            {{ $ins->estado === 'ACTIVA' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700' }}">
                            {{ $ins->estado ?? 'ACTIVA' }}
                        </span>

                        @if($ins->tarjeta)
                            <a href="{{ route('cajas.tarjeta', $ins->tarjeta->id) }}" 
                               target="_blank"
                               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-[#FF0066]/10 text-[#FF0066] hover:bg-[#FF0066]/20 transition-colors">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                                <span>Ver Tarjeta QR</span>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-8 text-center text-slate-400">
                    No se registran compras ni inscripciones para esta persona.
                </div>
            @endforelse
        </div>

        <!-- DESKTOP / TABLET: TABLA DE HISTORIAL (>= 768px) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-slate-800/60 text-[10px] uppercase tracking-wider font-bold text-slate-500 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-4 py-3">Comprobante #</th>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Disciplina & Modalidad</th>
                        <th class="px-4 py-3">Turno / Horario</th>
                        <th class="px-4 py-3">Docente</th>
                        <th class="px-4 py-3 text-right">Monto Pagado</th>
                        <th class="px-4 py-3 text-center">Estado</th>
                        <th class="px-4 py-3 text-right">Tarjeta QR</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($inscripciones as $ins)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <!-- N° Comprobante -->
                            <td class="px-4 py-3">
                                <span class="font-mono font-black text-slate-900 dark:text-white">
                                    {{ $ins->pago?->numero_factura ?? 'REC-S/N' }}
                                </span>
                            </td>

                            <!-- Fecha -->
                            <td class="px-4 py-3 text-slate-500">
                                {{ $ins->fecha_inscripcion ? $ins->fecha_inscripcion->format('d/m/Y') : ($ins->created_at ? $ins->created_at->format('d/m/Y') : '-') }}
                            </td>

                            <!-- Disciplina -->
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded text-[10px] bg-[#FF0066]/10 text-[#FF0066] font-black border border-[#FF0066]/30 inline-block">
                                    {{ $ins->horarioCurso?->disciplina?->nombre ?? 'DISCIPLINA' }}
                                </span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">
                                    {{ $ins->horarioCurso?->modalidad?->nombre ?? 'Regular' }}
                                </span>
                            </td>

                            <!-- Turno / Horario -->
                            <td class="px-4 py-3">
                                <span class="font-mono font-bold text-slate-800 dark:text-slate-200">
                                    {{ substr($ins->horarioCurso?->hora_inicio ?? '00:00', 0, 5) }} - {{ substr($ins->horarioCurso?->hora_fin ?? '00:00', 0, 5) }}
                                </span>
                                <span class="text-[10px] text-slate-400 block">
                                    {{ $ins->horarioCurso?->dias_semana ?? 'Lun a Vie' }}
                                </span>
                            </td>

                            <!-- Docente -->
                            <td class="px-4 py-3 text-slate-800 dark:text-slate-200 font-semibold">
                                {{ $ins->horarioCurso?->instructor?->persona?->nombre_completo ?? 'Sin Asignar' }}
                            </td>

                            <!-- Monto Pagado -->
                            <td class="px-4 py-3 text-right">
                                <span class="font-mono font-black text-emerald-600 dark:text-emerald-400 text-sm">
                                    Bs. {{ number_format((float) ($ins->pago?->importe_final ?? 0), 2) }}
                                </span>
                            </td>

                            <!-- Estado -->
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase inline-block
                                    {{ $ins->estado === 'ACTIVA' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300' }}">
                                    {{ $ins->estado ?? 'ACTIVA' }}
                                </span>
                            </td>

                            <!-- Tarjeta QR -->
                            <td class="px-4 py-3 text-right">
                                @if($ins->tarjeta)
                                    <a href="{{ route('cajas.tarjeta', $ins->tarjeta->id) }}" 
                                       target="_blank"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-[#FF0066]/10 text-[#FF0066] hover:bg-[#FF0066]/20 transition-colors"
                                       title="Ver Tarjeta de Asistencia QR">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                        </svg>
                                        <span>Tarjeta QR</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-[11px]">Sin Tarjeta</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-slate-400">
                                No se registran cobros ni inscripciones para este alumno.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
