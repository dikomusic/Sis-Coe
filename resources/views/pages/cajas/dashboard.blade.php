@extends('templates.erp-layout', ['title' => 'Dashboard de Cajas'])

@section('content')
<div 
    x-data="dashboardCajaApp()" 
    x-init="init()"
    class="max-w-6xl mx-auto space-y-4 sm:space-y-5"
>
    <!-- 1. Barra de Estado de Caja (Compacta y sin texto de relleno) -->
    <div class="bg-slate-900 border-l-4 border-l-[#FF0066] rounded-xl px-3.5 py-2.5 sm:px-4 sm:py-3 flex items-center justify-between gap-2 shadow-sm text-white">
        <div class="flex items-center gap-2 truncate">
            <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse shrink-0"></span>
            <div class="truncate text-xs sm:text-sm">
                <span class="font-black text-white">Ventanilla Única</span>
                <span class="text-slate-400 hidden sm:inline">• Cajero:</span>
                <span class="text-slate-300 font-bold truncate">{{ $stats['cajero_nombre'] }}</span>
            </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <span class="text-[11px] text-slate-400 uppercase font-bold hidden sm:inline">Cajón:</span>
            <span class="font-mono font-black text-xs sm:text-sm text-[#FF0066] bg-[#FF0066]/10 border border-[#FF0066]/30 px-2 py-0.5 rounded-lg">
                Bs. {{ number_format($stats['total_recaudado_hoy'], 2) }}
            </span>
        </div>
    </div>

    <!-- 2. APARTADO: Opciones Rápidas (El botón de cobrar está AQUÍ como botón principal) -->
    <div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-3">
            <!-- BOTÓN 1 PRINCIPAL: Cobro en Ventanilla -->
            <a 
                href="{{ route('cajas.ventanilla') }}" 
                class="p-3 sm:p-4 rounded-xl bg-gradient-to-r from-[#FF0066] to-[#E6005C] hover:brightness-105 text-white shadow-md shadow-[#FF0066]/25 transition-all active:scale-[0.98] flex items-center gap-3 group col-span-2 sm:col-span-1"
            >
                <div class="h-10 w-10 rounded-lg bg-white/20 flex items-center justify-center shrink-0">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="truncate">
                    <span class="block text-xs sm:text-sm font-black leading-tight">Cobro en Ventanilla</span>
                    <span class="text-[10px] font-bold text-white/80 uppercase tracking-wider">Inscripción / Pago</span>
                </div>
            </a>

            <!-- BOTÓN 2: + Nuevo Particular -->
            <button 
                type="button" 
                @click="abrirModalParticular = true"
                class="p-3 sm:p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-[#FF0066]/50 hover:bg-[#FF0066]/5 transition-all active:scale-[0.98] flex items-center gap-3 group shadow-sm text-left"
            >
                <div class="h-10 w-10 rounded-lg bg-[#FF0066]/10 text-[#FF0066] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <div class="truncate">
                    <span class="block text-xs sm:text-sm font-black text-slate-800 dark:text-white group-hover:text-[#FF0066] leading-tight">Nuevo Particular</span>
                    <span class="text-[10px] text-slate-400">Alumno civil</span>
                </div>
            </button>

            <!-- BOTÓN 3: Renovación Rápida -->
            <button 
                type="button" 
                @click="$refs.inputBuscador.focus()"
                class="p-3 sm:p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-[#EDD00A]/60 hover:bg-[#EDD00A]/5 transition-all active:scale-[0.98] flex items-center gap-3 group shadow-sm text-left"
            >
                <div class="h-10 w-10 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-[#EDD00A] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
                <div class="truncate">
                    <span class="block text-xs sm:text-sm font-black text-slate-800 dark:text-white group-hover:text-amber-500 leading-tight">Renovación Rápida</span>
                    <span class="text-[10px] text-slate-400">Buscar socio</span>
                </div>
            </button>

            <!-- BOTÓN 4: Reprogramar Clase -->
            <a 
                href="{{ route('cajas.reprogramaciones') }}" 
                class="p-3 sm:p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-blue-500/50 hover:bg-blue-50/50 transition-all active:scale-[0.98] flex items-center gap-3 group shadow-sm text-left"
            >
                <div class="h-10 w-10 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="truncate">
                    <span class="block text-xs sm:text-sm font-black text-slate-800 dark:text-white group-hover:text-blue-600 leading-tight">Reprogramar</span>
                    <span class="text-[10px] text-slate-400">Salud / Viaje</span>
                </div>
            </a>
        </div>
    </div>

    <!-- 3. APARTADO: Buscador Rápido (QR / CI / Nombre) - Sin texto innecesario -->
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 sm:p-4 shadow-sm space-y-2.5">
        <div class="flex items-center justify-between px-1">
            <span class="text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300">Buscar Alumno (CI / QR / Nombre)</span>
            <span class="text-[10px] text-slate-400">Presione <kbd class="px-1 rounded bg-slate-100 dark:bg-slate-800 font-mono font-bold">Enter</kbd></span>
        </div>

        <div class="flex items-center gap-2">
            <div class="relative flex-1">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    x-ref="inputBuscador"
                    x-model="criterioBusqueda"
                    @keydown.enter.prevent="ejecutarBusqueda()"
                    placeholder="Escriba CI, nombre o código..."
                    autocomplete="off"
                    class="block w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60 py-2.5 pl-9 pr-16 text-xs sm:text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-[#FF0066] focus:ring-2 focus:ring-[#FF0066]/20 transition-all font-medium"
                >
                <div class="absolute inset-y-0 right-0 flex items-center pr-1.5">
                    <template x-if="buscando">
                        <span class="mr-2">@include('atoms.spinner', ['size' => 'sm', 'color' => 'pink'])</span>
                    </template>
                    <button 
                        type="button" 
                        @click="ejecutarBusqueda()"
                        class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-900 text-white hover:bg-slate-800"
                    >
                        Buscar
                    </button>
                </div>
            </div>

            <!-- Botón de Escáner QR de Cámara -->
            <button 
                type="button" 
                @click="abrirEscanerQr()"
                class="px-3 py-2.5 rounded-xl text-xs font-bold bg-[#FF0066]/10 text-[#FF0066] hover:bg-[#FF0066]/20 border border-[#FF0066]/30 flex items-center gap-1.5 shrink-0 transition-colors"
                title="Escanear con cámara"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                </svg>
                <span>Escanear QR</span>
            </button>
        </div>

        <!-- Ficha de Resultado de Búsqueda -->
        <template x-if="personaEncontrada">
            <div class="rounded-xl border-2 border-[#FF0066] bg-[#FF0066]/5 dark:bg-[#FF0066]/10 p-3 transition-all space-y-2">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2.5 truncate">
                        <div class="h-9 w-9 rounded-lg bg-[#FF0066] flex items-center justify-center text-white font-black text-sm shrink-0">
                            <span x-text="personaEncontrada.nombre_completo.charAt(0)"></span>
                        </div>
                        <div class="truncate">
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white truncate" x-text="personaEncontrada.nombre_completo"></h4>
                                <span 
                                    class="px-1.5 py-0.2 text-[9px] font-bold rounded uppercase"
                                    :class="{
                                        'bg-[#FF0066]/20 text-[#FF0066]': personaEncontrada.tipo_persona === 'SOCIO',
                                        'bg-emerald-100 text-emerald-800': personaEncontrada.tipo_persona === 'BENEFICIARIO',
                                        'bg-amber-100 text-amber-800': personaEncontrada.tipo_persona === 'PARTICULAR'
                                    }"
                                    x-text="personaEncontrada.tipo_persona"
                                ></span>
                            </div>
                            <p class="text-[11px] text-slate-500">
                                CI: <span class="font-mono font-bold" x-text="personaEncontrada.ci"></span>
                                <template x-if="personaEncontrada.ultima_disciplina">
                                    <span> • Curso: <strong class="text-[#FF0066]" x-text="personaEncontrada.ultima_disciplina"></strong></span>
                                </template>
                            </p>
                        </div>
                    </div>

                    <a 
                        :href="'{{ route('cajas.ventanilla') }}?persona_id=' + personaEncontrada.id"
                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-[#FF0066] hover:bg-[#E6005C] text-white font-bold text-xs shrink-0 shadow-sm"
                    >
                        <span>Cobrar</span>
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </template>
    </div>

    <!-- 4. APARTADO: Monitor de Cupos y Disponibilidad Rápida (Optimizado para Celular) -->
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 sm:p-5 shadow-sm space-y-3">
        <!-- Cabecera de Disponibilidad con Filtro en 1 Toque -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 border-b border-slate-100 dark:border-slate-800 pb-2.5">
            <div class="flex items-center justify-between sm:justify-start gap-2">
                <div class="flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <h3 class="font-black text-slate-900 dark:text-white text-xs sm:text-sm uppercase tracking-wider">Disponibilidad en Vivo</h3>
                </div>
                <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-500">
                    <span x-text="horariosFiltrados.length"></span> turnos
                </span>
            </div>

            <!-- Filtro de Estado Rápido: [Todos | Con Cupo | Llenos] -->
            <div class="flex items-center gap-1">
                <button 
                    type="button" 
                    @click="filtroCupo = 'TODOS'"
                    class="px-2 py-1 rounded-lg text-[11px] font-bold transition-all"
                    :class="filtroCupo === 'TODOS' ? 'bg-slate-900 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'"
                >
                    Todos
                </button>
                <button 
                    type="button" 
                    @click="filtroCupo = 'DISPONIBLES'"
                    class="px-2.5 py-1 rounded-lg text-[11px] font-bold transition-all flex items-center gap-1.5"
                    :class="filtroCupo === 'DISPONIBLES' ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-emerald-600'"
                >
                    <span class="h-1.5 w-1.5 rounded-full" :class="filtroCupo === 'DISPONIBLES' ? 'bg-white' : 'bg-emerald-500'"></span>
                    <span>Con Cupo</span>
                </button>
                <button 
                    type="button" 
                    @click="filtroCupo = 'CRITICOS'"
                    class="px-2.5 py-1 rounded-lg text-[11px] font-bold transition-all flex items-center gap-1.5"
                    :class="filtroCupo === 'CRITICOS' ? 'bg-rose-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-rose-600'"
                >
                    <span class="h-1.5 w-1.5 rounded-full" :class="filtroCupo === 'CRITICOS' ? 'bg-white' : 'bg-rose-500'"></span>
                    <span>Llenos / Críticos</span>
                </button>
            </div>
        </div>

        <!-- Filtro Horizontal Deslizable por Disciplina (Táctil en Celular) -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 no-scrollbar text-xs">
            <button 
                type="button" 
                @click="filtroDisciplina = 'TODAS'"
                class="px-3 py-1.5 rounded-lg font-bold shrink-0 transition-all text-xs"
                :class="filtroDisciplina === 'TODAS' ? 'bg-[#FF0066] text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300'"
            >
                TODAS
            </button>
            @foreach($disciplinas as $disc)
                <button 
                    type="button" 
                    @click="filtroDisciplina = '{{ $disc->nombre }}'"
                    class="px-3 py-1.5 rounded-lg font-bold shrink-0 transition-all text-xs"
                    :class="filtroDisciplina === '{{ $disc->nombre }}' ? 'bg-[#FF0066] text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200'"
                >
                    {{ $disc->nombre }}
                </button>
            @endforeach
        </div>

        <!-- Lista Compacta de Turnos (Filas Delgadas: Máxima velocidad visual sin scroll) -->
        <div class="space-y-1.5 max-h-[380px] overflow-y-auto pr-1">
            <template x-for="item in horariosFiltrados" :key="item.id">
                <div 
                    class="rounded-xl border p-2.5 sm:p-3 flex items-center justify-between gap-3 transition-all"
                    :class="{
                        'border-rose-300/80 bg-rose-50/60 dark:bg-rose-950/20': item.estado_cupo === 'AGOTADO',
                        'border-amber-300/80 bg-amber-50/50 dark:bg-amber-950/20': item.estado_cupo === 'CRITICO',
                        'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/60 hover:border-slate-300': item.estado_cupo === 'DISPONIBLE'
                    }"
                >
                    <div class="flex items-center gap-2.5 min-w-0">
                        <!-- Indicador de color lateral -->
                        <div class="w-1.5 h-8 rounded-full shrink-0"
                             :class="item.disponibles <= 0 ? 'bg-rose-500' : (item.disponibles <= 3 ? 'bg-amber-500' : 'bg-emerald-500')">
                        </div>

                        <div class="truncate">
                            <div class="flex items-center gap-1.5">
                                <span class="font-mono font-black text-xs sm:text-sm text-slate-900 dark:text-white" x-text="item.rango_horario"></span>
                                <span class="text-[10px] font-extrabold uppercase px-1.5 py-0.2 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 truncate" x-text="item.disciplina"></span>
                            </div>
                            <p class="text-[11px] text-slate-400 truncate mt-0.5" x-text="item.dias + ' • Prof. ' + item.instructor"></p>
                        </div>
                    </div>

                    <div class="text-right shrink-0">
                        <span 
                            class="inline-block px-2 py-0.5 text-[10px] sm:text-xs font-black rounded-lg uppercase"
                            :class="{
                                'bg-rose-100 text-rose-800 border border-rose-300': item.estado_cupo === 'AGOTADO',
                                'bg-amber-100 text-amber-800 border border-amber-300': item.estado_cupo === 'CRITICO',
                                'bg-emerald-100 text-emerald-800 border border-emerald-300': item.estado_cupo === 'DISPONIBLE'
                            }"
                            x-text="item.disponibles <= 0 ? 'AGOTADO' : (item.disponibles + ' LIBRES')"
                        ></span>
                        <span class="block text-[10px] font-mono text-slate-400 mt-0.5" x-text="item.ocupados + '/' + item.capacidad + ' alumnos'"></span>
                    </div>
                </div>
            </template>

            <template x-if="horariosFiltrados.length === 0">
                <div class="py-6 text-center text-xs text-slate-400 border border-dashed rounded-xl">
                    No se encontraron turnos con los filtros seleccionados.
                </div>
            </template>
        </div>
    </div>

    <!-- 5. APARTADO: Flujo Diario y Dinero en Cajón -->
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 sm:p-5 shadow-sm space-y-3">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
            <span class="text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300">Flujo de Efectivo en Turno</span>
            <a href="{{ route('cajas.reportes') }}" class="text-xs font-bold text-[#FF0066] hover:underline flex items-center gap-1">
                <span>Arqueo Diario</span>
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-3 gap-2 sm:gap-3 text-center">
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 p-2.5">
                <span class="text-[10px] sm:text-xs text-slate-400 font-bold block uppercase">En Cajón</span>
                <p class="text-sm sm:text-xl font-black text-[#FF0066] mt-0.5 font-mono">Bs. {{ number_format($stats['total_recaudado_hoy'], 2) }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 p-2.5">
                <span class="text-[10px] sm:text-xs text-slate-400 font-bold block uppercase">Cobros</span>
                <p class="text-sm sm:text-xl font-black text-slate-800 dark:text-white mt-0.5 font-mono">{{ $stats['inscripciones_hoy'] }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 p-2.5">
                <span class="text-[10px] sm:text-xs text-slate-400 font-bold block uppercase">Descuentos</span>
                <p class="text-sm sm:text-xl font-black text-emerald-600 mt-0.5 font-mono">Bs. {{ number_format($stats['descuentos_hoy'], 2) }}</p>
            </div>
        </div>
    </div>

    <!-- 6. APARTADO: Historial de Últimos Movimientos (Abajo del todo, secundario) -->
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden" x-data="{ abierto: true }">
        <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between cursor-pointer" @click="abierto = !abierto">
            <span class="text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300">Últimos Movimientos del Día</span>
            <div class="flex items-center gap-2">
                <a href="{{ route('cajas.historial') }}" class="text-xs font-bold text-[#FF0066] hover:underline" @click.stop>Ver todo →</a>
                <svg class="h-4 w-4 text-slate-400 transition-transform" :class="abierto ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        <div x-show="abierto" class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-slate-800/50 text-[10px] uppercase tracking-wider font-bold text-slate-500 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-4 py-2.5">Recibo</th>
                        <th class="px-4 py-2.5">Alumno</th>
                        <th class="px-4 py-2.5">Curso</th>
                        <th class="px-4 py-2.5 text-right">Importe</th>
                        <th class="px-4 py-2.5 text-right">Tarjeta</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($ultimasInscripciones as $ins)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40">
                            <td class="px-4 py-2.5 font-mono">
                                <span class="font-bold text-slate-900 dark:text-white">{{ $ins->pago?->numero_factura ?? $ins->codigo_inscripcion }}</span>
                                <span class="block text-[10px] text-slate-400">{{ $ins->created_at->format('H:i') }}</span>
                            </td>
                            <td class="px-4 py-2.5 truncate max-w-[140px]">
                                <span class="font-bold text-slate-800 dark:text-white block truncate">{{ $ins->persona->nombre_completo }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $ins->persona->ci_completo }}</span>
                            </td>
                            <td class="px-4 py-2.5 truncate max-w-[120px] text-slate-600 dark:text-slate-300">
                                {{ $ins->horarioCurso?->disciplina?->nombre ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-2.5 text-right font-mono font-bold text-[#FF0066]">
                                Bs. {{ number_format($ins->pago?->importe_final ?? 0, 2) }}
                            </td>
                            <td class="px-4 py-2.5 text-right">
                                @if($ins->tarjeta)
                                    <a href="{{ route('cajas.tarjeta', $ins->tarjeta->id) }}" 
                                       class="px-2 py-1 rounded bg-[#FF0066]/10 text-[#FF0066] font-bold text-[10px] hover:bg-[#FF0066]/20">
                                        Ver
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-400 text-xs">
                                Sin transacciones registradas hoy.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de Alta Rápida de Particular -->
    <div 
        x-show="abrirModalParticular" 
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
        x-cloak
    >
        <div 
            @click.away="abrirModalParticular = false"
            class="bg-white dark:bg-slate-900 rounded-2xl max-w-sm w-full p-5 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-3.5"
        >
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-2.5">
                <h3 class="font-bold text-sm text-slate-900 dark:text-white">Nuevo Alumno Particular</h3>
                <button type="button" @click="abrirModalParticular = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-2.5 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Nombres *</label>
                    <input type="text" x-model="nuevoParticular.nombres" placeholder="Nombres" class="block w-full rounded-xl border-slate-300 py-2 px-3 text-xs">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Apellidos *</label>
                    <input type="text" x-model="nuevoParticular.apellidos" placeholder="Apellidos" class="block w-full rounded-xl border-slate-300 py-2 px-3 text-xs">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">CI *</label>
                        <input type="text" x-model="nuevoParticular.ci" placeholder="Número CI" class="block w-full rounded-xl border-slate-300 py-2 px-3 text-xs">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Expedido</label>
                        <select x-model="nuevoParticular.expedido" class="block w-full rounded-xl border-slate-300 py-2 px-3 text-xs">
                            <option value="LP">LP</option>
                            <option value="CB">CB</option>
                            <option value="SC">SC</option>
                            <option value="OR">OR</option>
                            <option value="PT">PT</option>
                            <option value="CH">CH</option>
                            <option value="TJ">TJ</option>
                            <option value="BN">BN</option>
                            <option value="PA">PA</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Celular</label>
                    <input type="text" x-model="nuevoParticular.telefono" placeholder="Ej. 71234567" class="block w-full rounded-xl border-slate-300 py-2 px-3 text-xs">
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                <button type="button" @click="abrirModalParticular = false" class="px-3 py-1.5 rounded-xl text-xs font-bold border border-slate-300">
                    Cancelar
                </button>
                <button type="button" @click="guardarNuevoParticular()" :disabled="guardandoParticular" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-[#FF0066] hover:bg-[#E6005C] text-white shadow-sm flex items-center gap-1">
                    <template x-if="guardandoParticular">
                        @include('atoms.spinner', ['size' => 'sm', 'color' => 'white'])
                    </template>
                    <span>Guardar y Cobrar</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Escáner QR de Cámara -->
    <div 
        x-show="modalEscanerQr" 
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4"
        x-cloak
    >
        <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-xs w-full p-4 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-3">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                <h3 class="font-bold text-xs text-slate-900 dark:text-white uppercase tracking-wider">Escanear Tarjeta COE</h3>
                <button type="button" @click="cerrarEscanerQr()" class="text-slate-400 hover:text-slate-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="relative w-full aspect-square bg-slate-950 rounded-xl overflow-hidden border-2 border-dashed border-[#FF0066]">
                <div id="qr-reader-container" class="w-full h-full"></div>
            </div>

            <p class="text-[11px] text-slate-400 text-center">Apunte al QR reverso de la tarjeta.</p>
        </div>
    </div>
</div>

<!-- Scripts de Búsqueda y Filtros Reactivos -->
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
function dashboardCajaApp() {
    return {
        criterioBusqueda: '',
        buscando: false,
        personaEncontrada: null,
        
        filtroDisciplina: 'TODAS',
        filtroCupo: 'TODOS',
        todosHorarios: @json($horariosCursos ?? []),

        abrirModalParticular: false,
        guardandoParticular: false,
        nuevoParticular: {
            nombres: '',
            apellidos: '',
            ci: '',
            expedido: 'LP',
            telefono: '',
            email: ''
        },

        modalEscanerQr: false,
        html5QrCodeScanner: null,

        init() {},

        get horariosFiltrados() {
            let lista = this.todosHorarios;

            if (this.filtroDisciplina !== 'TODAS') {
                lista = lista.filter(h => h.disciplina === this.filtroDisciplina);
            }

            if (this.filtroCupo === 'DISPONIBLES') {
                lista = lista.filter(h => h.disponibles > 0);
            } else if (this.filtroCupo === 'CRITICOS') {
                lista = lista.filter(h => h.disponibles <= 3);
            }

            return lista;
        },

        async ejecutarBusqueda() {
            const q = this.criterioBusqueda.trim();
            if (q.length < 2) return;

            this.buscando = true;
            this.personaEncontrada = null;

            try {
                const res = await fetch(`{{ route('cajas.api.buscarPersona') }}?q=${encodeURIComponent(q)}`);
                if (res.ok) {
                    const data = await res.json();
                    if (data && data.length > 0) {
                        this.personaEncontrada = data[0];
                    }
                }
            } catch (e) {
                console.error('Error al buscar:', e);
            } finally {
                this.buscando = false;
            }
        },

        abrirEscanerQr() {
            this.modalEscanerQr = true;
            this.$nextTick(() => {
                this.iniciarCamara();
            });
        },

        cerrarEscanerQr() {
            if (this.html5QrCodeScanner) {
                this.html5QrCodeScanner.stop().then(() => {
                    this.html5QrCodeScanner.clear();
                }).catch(() => {});
            }
            this.modalEscanerQr = false;
        },

        iniciarCamara() {
            const qrContainer = document.getElementById('qr-reader-container');
            if (!qrContainer) return;

            this.html5QrCodeScanner = new Html5Qrcode("qr-reader-container");
            this.html5QrCodeScanner.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 180, height: 180 } },
                (decodedText) => {
                    this.criterioBusqueda = decodedText;
                    this.cerrarEscanerQr();
                    this.ejecutarBusqueda();
                },
                () => {}
            ).catch(() => {});
        },

        async guardarNuevoParticular() {
            if (!this.nuevoParticular.nombres || !this.nuevoParticular.apellidos || !this.nuevoParticular.ci) {
                alert('Complete los campos obligatorios: Nombres, Apellidos y CI.');
                return;
            }

            this.guardandoParticular = true;
            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const res = await fetch('{{ route("cajas.api.registrarParticular") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.nuevoParticular)
                });

                if (res.ok) {
                    const data = await res.json();
                    window.location.href = `{{ route('cajas.ventanilla') }}?persona_id=${data.persona.id}`;
                } else {
                    const err = await res.json();
                    alert('Error: ' + (err.message || 'Verifique los datos ingresados.'));
                }
            } catch (e) {
                console.error(e);
                alert('Error al conectar con el servidor.');
            } finally {
                this.guardandoParticular = false;
            }
        }
    }
}
</script>
@endsection
