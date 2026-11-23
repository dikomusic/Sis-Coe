@extends('templates.erp-layout', ['title' => 'Padrón de Alumnos Únicos'])

@section('content')
<div class="space-y-6">
    <!-- Header Principal -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#FF0066]/10 text-[#FF0066] border border-[#FF0066]/20">
                    Padrón Maestro Único
                </span>
                <span class="text-xs font-semibold text-slate-400">Directorio Consolidado</span>
            </div>
            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight mt-1">
                Padrón de Alumnos y Clientes
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Cada persona es un perfil único con su historial integral de compras, cursos matriculados y tarjetas QR emitidas.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('cajas.ventanilla') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#FF0066] hover:bg-[#E6005C] text-white font-black text-xs shadow-md shadow-[#FF0066]/25 transition-all active:scale-95">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span>Nueva Inscripción en Ventanilla</span>
            </a>
        </div>
    </div>

    <!-- Tarjetas de Métricas Globales (Atomic Design: Organism) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- 1. Total Alumnos Únicos -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-3.5">
            <div class="h-11 w-11 rounded-xl bg-[#FF0066]/10 text-[#FF0066] flex items-center justify-center shrink-0">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="truncate">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Alumnos Únicos</span>
                <span class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white block">{{ number_format($totalAlumnosUnicos) }}</span>
                <span class="text-[10px] text-slate-500">Perfiles registrados</span>
            </div>
        </div>

        <!-- 2. Particulares Civiles -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-3.5">
            <div class="h-11 w-11 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div class="truncate">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Particulares Civiles</span>
                <span class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white block">{{ number_format($totalParticularesUnicos) }}</span>
                <span class="text-[10px] text-amber-600 dark:text-amber-400 font-semibold">Alumnos externos</span>
            </div>
        </div>

        <!-- 3. Socios y Beneficiarios -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-3.5">
            <div class="h-11 w-11 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div class="truncate">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Socios & Familias</span>
                <span class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white block">{{ number_format($totalSociosUnicos) }}</span>
                <span class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold">Personal militar / familia</span>
            </div>
        </div>

        <!-- 4. Recaudación Acumulada -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-3.5">
            <div class="h-11 w-11 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="truncate">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Recaudado Histórico</span>
                <span class="text-lg sm:text-xl font-black text-emerald-600 dark:text-emerald-400 block">Bs. {{ number_format($totalRecaudacionGeneral, 2) }}</span>
                <span class="text-[10px] text-slate-500">Caja acumulada</span>
            </div>
        </div>
    </div>

    <!-- Barra de Filtros, Búsqueda y Ordenamiento (Responsive) -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-3">
        <form method="GET" action="{{ route('cajas.alumnos') }}" class="space-y-3">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <!-- Buscador de Texto (predictivo por CI, Nombre, Apellido, Celular) -->
                <div class="md:col-span-6 relative">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $buscar }}" 
                        placeholder="Buscar por Carnet de Identidad, Nombre, Apellido o Celular..." 
                        class="w-full py-2.5 pl-10 pr-4 text-xs font-bold rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:border-[#FF0066] focus:ring-1 focus:ring-[#FF0066] transition-all"
                    >
                    <svg class="h-4 w-4 text-slate-400 absolute left-3.5 top-3.5 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <!-- Selector de Categoría -->
                <div class="md:col-span-3">
                    <select 
                        name="tipo" 
                        onchange="this.form.submit()" 
                        class="w-full py-2.5 px-3 text-xs font-bold rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white"
                    >
                        <option value="TODOS" {{ $tipo === 'TODOS' ? 'selected' : '' }}>Todas las Categorías</option>
                        <option value="PARTICULAR" {{ $tipo === 'PARTICULAR' ? 'selected' : '' }}>Particulares Civiles</option>
                        <option value="SOCIO" {{ $tipo === 'SOCIO' ? 'selected' : '' }}>Socios Titulares</option>
                        <option value="BENEFICIARIO" {{ $tipo === 'BENEFICIARIO' ? 'selected' : '' }}>Beneficiarios</option>
                    </select>
                </div>

                <!-- Selector de Ordenamiento -->
                <div class="md:col-span-3">
                    <select 
                        name="orden" 
                        onchange="this.form.submit()" 
                        class="w-full py-2.5 px-3 text-xs font-bold rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white"
                    >
                        <option value="mas_activos" {{ $orden === 'mas_activos' ? 'selected' : '' }}>Más Frecuentes / Activos</option>
                        <option value="mas_inversion" {{ $orden === 'mas_inversion' ? 'selected' : '' }}>Mayor Inversión (Bs.)</option>
                        <option value="alfabetico" {{ $orden === 'alfabetico' ? 'selected' : '' }}>Alfabético (A - Z)</option>
                        <option value="recientes" {{ $orden === 'recientes' ? 'selected' : '' }}>Más Recientes</option>
                    </select>
                </div>
            </div>

            <!-- Fila de Filtros Rápidos en 1 Toque y Botón Limpiar -->
            <div class="flex items-center justify-between flex-wrap gap-2 pt-2 border-t border-slate-100 dark:border-slate-800 text-xs">
                <div class="flex items-center gap-1.5 flex-wrap">
                    <span class="text-[11px] font-bold text-slate-400 mr-1">Filtro rápido:</span>
                    <a href="{{ route('cajas.alumnos', ['tipo' => 'TODOS']) }}" 
                       class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all {{ $tipo === 'TODOS' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200' }}">
                        Todos
                    </a>
                    <a href="{{ route('cajas.alumnos', ['tipo' => 'PARTICULAR']) }}" 
                       class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all {{ $tipo === 'PARTICULAR' ? 'bg-amber-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200' }}">
                        Particulares
                    </a>
                    <a href="{{ route('cajas.alumnos', ['tipo' => 'SOCIO']) }}" 
                       class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all {{ $tipo === 'SOCIO' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200' }}">
                        Socios
                    </a>
                    <a href="{{ route('cajas.alumnos', ['tipo' => 'BENEFICIARIO']) }}" 
                       class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all {{ $tipo === 'BENEFICIARIO' ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200' }}">
                        Beneficiarios
                    </a>
                </div>

                <div class="flex items-center gap-2">
                    @if(!empty($buscar) || $tipo !== 'TODOS' || $orden !== 'mas_activos')
                        <a href="{{ route('cajas.alumnos') }}" 
                           class="text-xs text-rose-500 hover:text-rose-700 font-bold underline">
                            Limpiar filtros
                        </a>
                    @endif
                    <button type="submit" class="px-4 py-1.5 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-xs font-bold hover:bg-slate-800 transition-colors">
                        Buscar
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- CONTENEDOR MULTI-PANTALLA: CARDS EN CELULAR, TABLA EN COMPUTADORA -->
    
    <!-- VISTA MÓVIL (Cards táctiles < 768px) -->
    <div class="block md:hidden space-y-3">
        @forelse($alumnos as $alumno)
            <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3 truncate">
                        <div class="h-11 w-11 rounded-xl bg-[#FF0066]/10 text-[#FF0066] border border-[#FF0066]/20 font-black flex items-center justify-center text-sm shrink-0">
                            {{ strtoupper(substr($alumno->nombres ?? 'A', 0, 1)) }}
                        </div>
                        <div class="truncate">
                            <h4 class="font-black text-sm text-slate-900 dark:text-white truncate">
                                {{ $alumno->nombre_completo }}
                            </h4>
                            <p class="text-[11px] text-slate-500 font-mono">
                                CI: <span class="font-bold text-slate-700 dark:text-slate-300">{{ $alumno->ci_completo }}</span>
                                @if($alumno->telf_cel)
                                    • Cel: {{ $alumno->telf_cel }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase shrink-0 
                        {{ $alumno->tipo_persona === 'SOCIO' || $alumno->tipo_persona === 'SOCIO_TITULAR' ? 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300' : '' }}
                        {{ $alumno->tipo_persona === 'BENEFICIARIO' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : '' }}
                        {{ $alumno->tipo_persona === 'PARTICULAR' ? 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300' : '' }}">
                        {{ $alumno->tipo_persona ?? 'PARTICULAR' }}
                    </span>
                </div>

                <!-- Resumen Financiero y Cursos del Alumno -->
                <div class="grid grid-cols-2 gap-2 bg-slate-50 dark:bg-slate-800/50 p-2.5 rounded-xl text-xs">
                    <div>
                        <span class="text-[10px] text-slate-400 uppercase font-bold block">Historial de Cursos</span>
                        <span class="font-black text-slate-900 dark:text-white inline-flex items-center gap-1">
                            <span class="text-[#FF0066]">{{ $alumno->inscripciones_count }}</span> inscripciones
                        </span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 uppercase font-bold block">Inversión Acumulada</span>
                        <span class="font-black text-emerald-600 dark:text-emerald-400">
                            Bs. {{ number_format((float) ($alumno->total_invertido ?? 0), 2) }}
                        </span>
                    </div>
                </div>

                <!-- Botones de Acción Móvil Grandes (>44px de toque) -->
                <div class="grid grid-cols-2 gap-2 pt-1 border-t border-slate-100 dark:border-slate-800">
                    <a href="{{ route('cajas.alumnos.detalle', $alumno->id) }}" 
                       class="flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs transition-colors">
                        <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span>Ver Historial</span>
                    </a>

                    <a href="{{ route('cajas.ventanilla', ['search' => $alumno->ci]) }}" 
                       class="flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl bg-[#FF0066] hover:bg-[#E6005C] text-white font-black text-xs shadow-sm transition-all active:scale-95">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span>Cobrar / Renovar</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-200 dark:border-slate-800 text-center space-y-3">
                <div class="h-12 w-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto text-slate-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h4 class="font-bold text-slate-800 dark:text-white text-sm">No se encontraron alumnos con ese criterio</h4>
                <p class="text-xs text-slate-500">Intente con otro carnet, apellido o limpie los filtros.</p>
            </div>
        @endforelse
    </div>

    <!-- VISTA DESKTOP / TABLET (Tabla de Alta Densidad >= 768px) -->
    <div class="hidden md:block bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-slate-800/60 text-[10px] uppercase tracking-wider font-bold text-slate-500 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-4 py-3.5">Alumno Único</th>
                        <th class="px-4 py-3.5">Carnet de Identidad</th>
                        <th class="px-4 py-3.5 text-center">Categoría</th>
                        <th class="px-4 py-3.5 text-center">Cursos / Pases</th>
                        <th class="px-4 py-3.5 text-right">Inversión Acumulada</th>
                        <th class="px-4 py-3.5 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($alumnos as $alumno)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <!-- Alumno / Cliente -->
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-xl bg-[#FF0066]/10 text-[#FF0066] border border-[#FF0066]/20 font-black flex items-center justify-center text-xs shrink-0">
                                        {{ strtoupper(substr($alumno->nombres ?? 'A', 0, 1)) }}
                                    </div>
                                    <div class="truncate max-w-xs">
                                        <a href="{{ route('cajas.alumnos.detalle', $alumno->id) }}" 
                                           class="font-black text-slate-900 dark:text-white hover:text-[#FF0066] transition-colors block truncate">
                                            {{ $alumno->nombre_completo }}
                                        </a>
                                        <span class="text-[11px] text-slate-400 block truncate">
                                            {{ $alumno->telf_cel ? 'Cel: ' . $alumno->telf_cel : 'Sin teléfono' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- CI -->
                            <td class="px-4 py-3">
                                <span class="font-mono font-bold text-slate-800 dark:text-slate-200">
                                    {{ $alumno->ci_completo }}
                                </span>
                            </td>

                            <!-- Categoría -->
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase inline-block
                                    {{ $alumno->tipo_persona === 'SOCIO' || $alumno->tipo_persona === 'SOCIO_TITULAR' ? 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300' : '' }}
                                    {{ $alumno->tipo_persona === 'BENEFICIARIO' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : '' }}
                                    {{ $alumno->tipo_persona === 'PARTICULAR' ? 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300' : '' }}">
                                    {{ $alumno->tipo_persona ?? 'PARTICULAR' }}
                                </span>
                            </td>

                            <!-- Cursos / Pases -->
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-black bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200">
                                    <span class="text-[#FF0066]">{{ $alumno->inscripciones_count }}</span> 
                                    <span class="text-[11px] font-semibold text-slate-400">{{ $alumno->inscripciones_count === 1 ? 'curso' : 'cursos' }}</span>
                                </span>
                            </td>

                            <!-- Inversión Acumulada -->
                            <td class="px-4 py-3 text-right">
                                <span class="font-mono font-black text-sm text-emerald-600 dark:text-emerald-400">
                                    Bs. {{ number_format((float) ($alumno->total_invertido ?? 0), 2) }}
                                </span>
                            </td>

                            <!-- Acciones -->
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('cajas.alumnos.detalle', $alumno->id) }}" 
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-colors"
                                       title="Ver Expediente e Historial Completo">
                                        <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span>Historial</span>
                                    </a>

                                    <a href="{{ route('cajas.ventanilla', ['search' => $alumno->ci]) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-black bg-[#FF0066] hover:bg-[#E6005C] text-white shadow-sm transition-all active:scale-95"
                                       title="Cobrar o Renovar en Ventanilla">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        <span>Cobrar</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-slate-400">
                                No se encontraron alumnos registrados con los filtros aplicados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginador -->
    <div class="pt-2">
        {{ $alumnos->links() }}
    </div>
</div>
@endsection
