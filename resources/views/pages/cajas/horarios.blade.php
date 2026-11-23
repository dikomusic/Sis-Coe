@extends('templates.erp-layout', ['title' => 'Horarios y Cupos en Vivo'])

@section('content')
<div class="space-y-4 sm:space-y-6" x-data="filtroHorarios()">
    <!-- Encabezado Institucional COE -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200 dark:border-slate-800 pb-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded bg-[#FF0066]/10 text-[#FF0066] border border-[#FF0066]/30">Control Operativo</span>
                <span class="text-xs text-slate-400 font-bold">• Dirección de Deportes COE</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight mt-1">Disponibilidad de Horarios y Aforos</h2>
            <p class="text-xs text-slate-500 mt-0.5">Supervisión en tiempo real de cupos por turno. Seleccione un turno para consultar la nómina de alumnos.</p>
        </div>

        <div class="flex items-center gap-2">
            <!-- Botón Cobro en Ventanilla -->
            <a href="{{ route('cajas.ventanilla') }}" 
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#FF0066] hover:bg-[#E6005C] text-white font-bold text-xs shadow-md shadow-[#FF0066]/25 transition-all active:scale-95 shrink-0">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span>Cobro en Ventanilla</span>
            </a>
        </div>
    </div>

    <!-- Barra de Control y Filtros (Atomic Design - Molécula de Búsqueda) -->
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-3.5 sm:p-4 shadow-sm space-y-3">
        <!-- Fila 1: Buscador universal + Pastillas de Estado + Conmutador de Vista -->
        <div class="flex flex-col md:flex-row items-stretch md:items-center gap-2.5">
            <!-- Buscador Rápido -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    x-model="criterio" 
                    placeholder="Filtrar por horario (ej. 15:00), docente (ej. Roberto) o disciplina..." 
                    class="block w-full pl-9 pr-8 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs sm:text-sm font-bold text-slate-900 dark:text-white placeholder-slate-400 focus:border-[#FF0066] focus:ring-[#FF0066] transition-all"
                >
                <button 
                    type="button" 
                    x-show="criterio.length > 0" 
                    @click="criterio = ''" 
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600"
                    title="Limpiar búsqueda"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Pastillas de Estado de Aforo -->
            <div class="grid grid-cols-3 sm:flex items-center gap-1 p-1 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 shrink-0">
                <button 
                    type="button" 
                    @click="filtroEstado = 'TODOS'" 
                    class="px-3 py-1.5 rounded-lg text-xs font-black transition-all text-center"
                    :class="filtroEstado === 'TODOS' ? 'bg-[#FF0066] text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'"
                >
                    Todos (<span x-text="todos.length"></span>)
                </button>
                <button 
                    type="button" 
                    @click="filtroEstado = 'DISPONIBLE'" 
                    class="px-3 py-1.5 rounded-lg text-xs font-black transition-all flex items-center justify-center gap-1.5"
                    :class="filtroEstado === 'DISPONIBLE' ? 'bg-emerald-600 text-white shadow-sm' : 'text-emerald-700 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/40'"
                >
                    <span class="h-2 w-2 rounded-full" :class="filtroEstado === 'DISPONIBLE' ? 'bg-white' : 'bg-emerald-500'"></span>
                    <span>Con Cupo (<span x-text="contarPorEstado('DISPONIBLE')"></span>)</span>
                </button>
                <button 
                    type="button" 
                    @click="filtroEstado = 'LLENO'" 
                    class="px-3 py-1.5 rounded-lg text-xs font-black transition-all flex items-center justify-center gap-1.5"
                    :class="filtroEstado === 'LLENO' ? 'bg-rose-600 text-white shadow-sm' : 'text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40'"
                >
                    <span class="h-2 w-2 rounded-full" :class="filtroEstado === 'LLENO' ? 'bg-white' : 'bg-rose-500'"></span>
                    <span>Agotados (<span x-text="contarPorEstado('LLENO')"></span>)</span>
                </button>
            </div>
        </div>

        <!-- Fila 2: Carrusel Horizontal de Disciplinas -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 no-scrollbar text-xs">
            <button 
                type="button" 
                @click="filtroDisciplina = 'TODAS'" 
                class="px-3.5 py-1.5 rounded-full font-black text-xs whitespace-nowrap transition-all shrink-0 active:scale-95"
                :class="filtroDisciplina === 'TODAS' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200'"
            >
                Todas las Disciplinas
            </button>

            @foreach($disciplinas as $d)
                <button 
                    type="button" 
                    @click="filtroDisciplina = '{{ addslashes($d->nombre) }}'" 
                    class="px-3.5 py-1.5 rounded-full font-bold text-xs whitespace-nowrap transition-all shrink-0 active:scale-95"
                    :class="filtroDisciplina === '{{ addslashes($d->nombre) }}' ? 'bg-[#FF0066] text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200'"
                >
                    {{ $d->nombre }} ({{ $d->horariosCursos->count() }})
                </button>
            @endforeach
        </div>

        <!-- Barra de Conteo Informativa -->
        <div class="flex items-center justify-between text-xs font-bold text-slate-500 pt-1 border-t border-slate-100 dark:border-slate-800">
            <span>Mostrando <span class="text-[#FF0066] font-black" x-text="turnosFiltrados.length"></span> turnos configurados</span>
            <span class="text-[11px] text-slate-400 hidden sm:inline">Haga clic en un turno para ver la nómina de alumnos</span>
        </div>
    </div>

    <!-- 1. VISTA EN LISTA PARA CELULAR (< 768px): Filas táctiles y compactas sin scroll horizontal -->
    <div x-show="turnosFiltrados.length > 0" class="block md:hidden divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <template x-for="item in turnosFiltrados" :key="'mob-' + item.id">
            <div @click="abrirListaAlumnos(item)" class="p-3.5 space-y-2.5 hover:bg-slate-50 dark:hover:bg-slate-800/50 cursor-pointer transition-colors active:bg-slate-100">
                <!-- Fila 1: Disciplina + Badge Estado -->
                <div class="flex items-center justify-between gap-2">
                    <span class="px-2 py-0.5 rounded text-[10px] bg-[#FF0066]/10 text-[#FF0066] font-black border border-[#FF0066]/30" x-text="item.disciplina_nombre"></span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase inline-flex items-center gap-1"
                          :class="{
                              'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300': item.estado === 'DISPONIBLE',
                              'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300': item.estado === 'CRITICO',
                              'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300': item.estado === 'LLENO'
                          }">
                        <span class="h-1.5 w-1.5 rounded-full"
                              :class="{
                                  'bg-emerald-500': item.estado === 'DISPONIBLE',
                                  'bg-amber-500': item.estado === 'CRITICO',
                                  'bg-rose-500': item.estado === 'LLENO'
                              }"></span>
                        <span x-text="item.estado === 'LLENO' ? 'Agotado' : item.cupos_libres + ' libres'"></span>
                    </span>
                </div>

                <!-- Fila 2: Horario y Docente -->
                <div class="flex items-center justify-between text-xs">
                    <div>
                        <span class="font-mono font-black text-slate-900 dark:text-white text-sm" x-text="item.horario_str"></span>
                        <span class="text-[11px] text-slate-400 block" x-text="item.dias + ' • ' + item.modalidad"></span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block" x-text="item.instructor_nombre"></span>
                        <span class="text-[10px] text-slate-400">Docente</span>
                    </div>
                </div>

                <!-- Fila 3: Aforo y Acciones -->
                <div class="pt-2 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="font-mono font-black text-xs text-slate-900 dark:text-white" x-text="item.cupo_actual + '/' + item.cupo_max"></span>
                        <span class="text-[10px] text-slate-400" x-text="'(' + item.total_ex_alumnos + ' ex-alumnos)'"></span>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <button type="button" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-[#FF0066]/10 hover:text-[#FF0066] transition-colors">
                            Ver (<span x-text="item.cupo_actual"></span>)
                        </button>
                        <a :href="'{{ route('cajas.ventanilla') }}'" @click.stop
                           class="px-3 py-1 rounded-lg text-xs font-black text-white shadow-sm transition-all"
                           :class="item.cupos_libres > 0 ? 'bg-[#FF0066] hover:bg-[#E6005C]' : 'bg-slate-200 text-slate-400 cursor-not-allowed pointer-events-none'">
                            Inscribir
                        </a>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- 2. VISTA EN TABLA DE ALTA DENSIDAD PARA COMPUTADORA Y TABLET (>= 768px) -->
    <div x-show="turnosFiltrados.length > 0" class="hidden md:block rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-slate-800/60 text-[10px] uppercase tracking-wider font-bold text-slate-500 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-4 py-3">Disciplina</th>
                        <th class="px-4 py-3">Horario</th>
                        <th class="px-4 py-3">Días / Frecuencia</th>
                        <th class="px-4 py-3">Docente</th>
                        <th class="px-4 py-3 text-center">Aforo en Vivo</th>
                        <th class="px-4 py-3 text-center">Estado</th>
                        <th class="px-4 py-3 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <template x-for="item in turnosFiltrados" :key="'desk-' + item.id">
                        <tr 
                            @click="abrirListaAlumnos(item)"
                            class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 cursor-pointer transition-colors group"
                        >
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded text-[10px] bg-[#FF0066]/10 text-[#FF0066] font-black border border-[#FF0066]/30 inline-block" 
                                      x-text="item.disciplina_nombre"></span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-mono font-black text-slate-900 dark:text-white text-xs" x-text="item.horario_str"></div>
                                <span class="text-[10px] text-slate-400" x-text="item.modalidad"></span>
                            </td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300 font-semibold" x-text="item.dias"></td>
                            <td class="px-4 py-3 text-slate-800 dark:text-slate-200 font-bold" x-text="item.instructor_nombre"></td>
                            <td class="px-4 py-3 text-center">
                                <span class="font-mono font-bold text-slate-900 dark:text-white" x-text="item.cupo_actual + '/' + item.cupo_max"></span>
                                <span class="text-[9px] text-slate-400 block" x-text="'(' + item.total_ex_alumnos + ' ex-alumnos)'"></span>
                                <div class="w-16 h-1.5 rounded-full bg-slate-100 dark:bg-slate-800 mx-auto mt-1 overflow-hidden">
                                    <div class="h-full rounded-full"
                                         :class="{
                                             'bg-emerald-500': item.porcentaje < 75,
                                             'bg-amber-500': item.porcentaje >= 75 && item.porcentaje < 100,
                                             'bg-rose-500': item.porcentaje >= 100
                                         }"
                                         :style="'width: ' + item.porcentaje + '%'">
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase inline-flex items-center gap-1"
                                      :class="{
                                          'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300': item.estado === 'DISPONIBLE',
                                          'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300': item.estado === 'CRITICO',
                                          'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300': item.estado === 'LLENO'
                                      }">
                                    <span class="h-1.5 w-1.5 rounded-full"
                                          :class="{
                                              'bg-emerald-500': item.estado === 'DISPONIBLE',
                                              'bg-amber-500': item.estado === 'CRITICO',
                                              'bg-rose-500': item.estado === 'LLENO'
                                          }"></span>
                                    <span x-text="item.estado === 'LLENO' ? 'Agotado' : item.cupos_libres + ' libres'"></span>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button 
                                        type="button" 
                                        class="px-2.5 py-1 rounded-lg text-[11px] font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 group-hover:bg-[#FF0066]/10 group-hover:text-[#FF0066] transition-colors"
                                    >
                                        Ver (<span x-text="item.cupo_actual"></span>)
                                    </button>
                                    <a :href="'{{ route('cajas.ventanilla') }}'" 
                                       @click.stop
                                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-black transition-all"
                                       :class="item.cupos_libres > 0 ? 'bg-[#FF0066] text-white hover:bg-[#E6005C]' : 'bg-slate-100 text-slate-400 cursor-not-allowed pointer-events-none'">
                                        <span>Cobrar</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Estado Vacío cuando no coinciden filtros -->
    <div x-show="turnosFiltrados.length === 0" class="rounded-2xl border border-dashed border-slate-300 dark:border-slate-800 p-8 text-center space-y-3 bg-white dark:bg-slate-900" x-cloak>
        <div class="h-10 w-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto text-slate-400">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h3 class="font-bold text-slate-800 dark:text-white text-sm">No se encontraron turnos con el filtro seleccionado</h3>
        <p class="text-xs text-slate-500 max-w-sm mx-auto">Modifique el texto de búsqueda o seleccione otra disciplina deportiva.</p>
        <button 
            type="button" 
            @click="resetearFiltros()" 
            class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-slate-800 transition-colors"
        >
            Restablecer Filtros
        </button>
    </div>

    <!-- MODAL / BOTTOM SHEET: Nómina de Alumnos Inscritos (Responsive Móvil y Desktop) -->
    <div 
        x-show="modalAlumnos" 
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-end sm:items-center justify-center p-0 sm:p-4 transition-opacity"
        x-cloak
    >
        <div 
            @click.away="modalAlumnos = false"
            class="bg-white dark:bg-slate-900 rounded-t-3xl sm:rounded-2xl max-w-2xl w-full p-4 sm:p-6 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-4 max-h-[85vh] sm:max-h-[80vh] flex flex-col transition-all"
        >
            <!-- Handle táctil para celular -->
            <div class="sm:hidden w-12 h-1.5 bg-slate-300 dark:bg-slate-700 rounded-full mx-auto -mt-1 mb-2"></div>

            <!-- Cabecera del Turno Seleccionado -->
            <template x-if="turnoSeleccionado">
                <div class="flex items-start justify-between border-b border-slate-100 dark:border-slate-800 pb-3 shrink-0">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 text-[10px] font-black uppercase rounded bg-[#FF0066]/10 text-[#FF0066] border border-[#FF0066]/30"
                                  x-text="turnoSeleccionado.disciplina_nombre"></span>
                            <span class="text-xs font-mono font-black text-slate-800 dark:text-white"
                                  x-text="turnoSeleccionado.horario_str"></span>
                        </div>
                        <h3 class="font-black text-base sm:text-lg text-slate-900 dark:text-white mt-1">
                            Nómina de Alumnos
                        </h3>
                        <p class="text-xs text-slate-500">
                            Docente: <strong class="text-slate-800 dark:text-slate-200" x-text="turnoSeleccionado.instructor_nombre"></strong> • 
                            <span x-text="turnoSeleccionado.dias"></span> • 
                            <strong class="text-emerald-600 font-bold" x-text="turnoSeleccionado.total_activos + ' alumnos activos'"></strong> 
                            <span class="text-slate-400" x-text="'(' + turnoSeleccionado.total_ex_alumnos + ' ex-alumnos)'"></span>
                        </p>
                    </div>

                    <button 
                        type="button" 
                        @click="modalAlumnos = false" 
                        class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors shrink-0"
                        title="Cerrar ventana"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </template>

            <!-- Sub-filtros: Activos Hoy vs Ex-Alumnos vs Todos -->
            <template x-if="turnoSeleccionado">
                <div class="flex items-center gap-1.5 p-1 bg-slate-100 dark:bg-slate-800 rounded-xl text-xs shrink-0">
                    <button type="button" @click="subfiltroModal = 'ACTIVOS'" 
                            class="flex-1 py-1.5 px-2.5 rounded-lg font-bold text-xs transition-all flex items-center justify-center gap-1.5"
                            :class="subfiltroModal === 'ACTIVOS' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white'">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        <span>Activos Hoy (<span x-text="turnoSeleccionado.total_activos"></span>)</span>
                    </button>
                    <button type="button" @click="subfiltroModal = 'EX_ALUMNOS'" 
                            class="flex-1 py-1.5 px-2.5 rounded-lg font-bold text-xs transition-all flex items-center justify-center gap-1.5"
                            :class="subfiltroModal === 'EX_ALUMNOS' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white'">
                        <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                        <span>Ex-Alumnos (<span x-text="turnoSeleccionado.total_ex_alumnos"></span>)</span>
                    </button>
                    <button type="button" @click="subfiltroModal = 'TODOS'" 
                            class="py-1.5 px-2.5 rounded-lg font-bold text-xs transition-all"
                            :class="subfiltroModal === 'TODOS' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white'">
                        <span>Todos (<span x-text="turnoSeleccionado.total_historico_personas"></span>)</span>
                    </button>
                </div>
            </template>

            <!-- Buscador interno de alumnos dentro del turno -->
            <template x-if="turnoSeleccionado && turnoSeleccionado.alumnos.length > 5">
                <div class="relative shrink-0">
                    <input 
                        type="text" 
                        x-model="busquedaAlumnoModal" 
                        placeholder="Filtrar por apellido, nombre o carnet de identidad..." 
                        class="block w-full py-2 pl-8 pr-3 text-xs font-bold rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400"
                    >
                    <svg class="h-3.5 w-3.5 text-slate-400 absolute left-2.5 top-3 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </template>

            <!-- Lista con Scroll Táctil Suave -->
            <div class="overflow-y-auto space-y-2 flex-1 pr-1 divide-y divide-slate-100 dark:divide-slate-800">
                <template x-if="turnoSeleccionado && alumnosFiltradosEnModal.length === 0">
                    <div class="py-10 text-center space-y-2">
                        <div class="h-10 w-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-slate-800 dark:text-white text-xs">No hay alumnos en esta vista</h4>
                        <p class="text-[11px] text-slate-500">
                            Cambie entre "Activos Hoy" o "Ex-Alumnos" en las pestañas superiores.
                        </p>
                    </div>
                </template>

                <template x-for="(alumno, idx) in alumnosFiltradosEnModal" :key="alumno.id">
                    <div class="pt-2.5 pb-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5 hover:bg-slate-50 dark:hover:bg-slate-800/40 px-2 rounded-xl transition-colors">
                        <div class="flex items-center gap-3 truncate">
                            <!-- Avatar con inicial -->
                            <div class="h-9 w-9 rounded-xl font-black flex items-center justify-center text-sm shrink-0 border"
                                 :class="alumno.es_activo ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-slate-100 text-slate-400 border-slate-200'">
                                <span x-text="alumno.nombre.charAt(0)"></span>
                            </div>

                            <div class="truncate">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="text-xs font-bold text-slate-400" x-text="(idx + 1) + '.'"></span>
                                    <h5 class="font-black text-xs sm:text-sm text-slate-900 dark:text-white truncate" x-text="alumno.nombre"></h5>
                                    
                                    <!-- Estado Vigente vs Ex-Alumno -->
                                    <span class="px-1.5 py-0.2 text-[9px] font-black rounded-full uppercase"
                                          :class="alumno.es_activo 
                                              ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-300' 
                                              : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 border border-slate-300'"
                                          x-text="alumno.es_activo ? 'Activo' : 'Ex-Alumno'">
                                    </span>

                                    <span class="px-1.5 py-0.2 text-[9px] font-black rounded uppercase"
                                          :class="{
                                              'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300': alumno.tipo_persona === 'SOCIO',
                                              'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300': alumno.tipo_persona === 'BENEFICIARIO',
                                              'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300': alumno.tipo_persona === 'PARTICULAR'
                                          }"
                                          x-text="alumno.tipo_persona">
                                    </span>

                                    <!-- Pill de múltiples renovaciones si tiene más de 1 pago en este turno -->
                                    <template x-if="alumno.total_inscripciones > 1">
                                        <span class="px-1.5 py-0.2 text-[9px] font-black rounded-full bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300 border border-purple-200 dark:border-purple-800"
                                              x-text="alumno.total_inscripciones + ' pagos'">
                                        </span>
                                    </template>
                                </div>

                                <p class="text-[11px] text-slate-500 truncate mt-0.5">
                                    CI: <span class="font-mono font-bold" x-text="alumno.ci"></span> • 
                                    Último Comprobante: <strong class="font-mono text-slate-800 dark:text-slate-200" x-text="alumno.factura"></strong> • 
                                    <span class="text-emerald-600 font-bold" x-text="'Bs. ' + alumno.monto.toFixed(2)"></span>
                                    <template x-if="alumno.total_inscripciones > 1">
                                        <span class="text-slate-400 text-[10px]" x-text="' (Total: Bs. ' + alumno.total_pagado.toFixed(2) + ')'"></span>
                                    </template>
                                </p>
                            </div>
                        </div>

                        <!-- Botones de Acción: Ver Historial Completo & Renovar / Tarjeta QR -->
                        <div class="shrink-0 flex items-center gap-1.5 self-end sm:self-center">
                            <!-- Si es ex-alumno, botón rápido para renovar en ventanilla -->
                            <template x-if="!alumno.es_activo">
                                <a :href="'{{ route('cajas.ventanilla') }}?search=' + alumno.ci" 
                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-black bg-[#FF0066] hover:bg-[#E6005C] text-white shadow-sm transition-all"
                                   title="Renovar matrícula para el mes en curso">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    <span>Renovar</span>
                                </a>
                            </template>

                            <!-- Enlace al Historial Único de la Persona -->
                            <a :href="'{{ url('/cajas/alumnos') }}/' + alumno.persona_id" 
                               class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-colors"
                               title="Ver Historial Completo de este Alumno Único">
                                <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span>Historial</span>
                            </a>

                            <!-- Botón Tarjeta QR -->
                            <template x-if="alumno.tarjeta_id">
                                <a :href="'{{ url('/cajas/tarjeta') }}/' + alumno.tarjeta_id" 
                                   target="_blank"
                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold bg-[#FF0066]/10 text-[#FF0066] hover:bg-[#FF0066]/20 transition-colors"
                                   title="Ver Tarjeta de Asistencia QR">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                    </svg>
                                    <span>Tarjeta QR</span>
                                </a>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Pie del Modal con Cierre -->
            <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between shrink-0">
                <span class="text-xs text-slate-400">SISCOE • Ventanilla de Caja</span>
                <button 
                    type="button" 
                    @click="modalAlumnos = false" 
                    class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-slate-800 transition-colors"
                >
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Lógica Reactiva Alpine.js (0 ms Instantáneo) -->
<script>
function filtroHorarios() {
    return {
        todos: @json($todosTurnos ?? []),
        criterio: '',
        filtroDisciplina: 'TODAS',
        filtroEstado: 'TODOS',
        modalAlumnos: false,
        turnoSeleccionado: null,
        busquedaAlumnoModal: '',
        subfiltroModal: 'ACTIVOS',

        get turnosFiltrados() {
            const q = this.criterio.toLowerCase().trim();

            return this.todos.filter(t => {
                // Filtro por Disciplina
                if (this.filtroDisciplina !== 'TODAS' && t.disciplina_nombre !== this.filtroDisciplina) {
                    return false;
                }

                // Filtro por Estado de Cupo
                if (this.filtroEstado === 'DISPONIBLE' && t.cupos_libres === 0) {
                    return false;
                }
                if (this.filtroEstado === 'LLENO' && t.cupos_libres > 0) {
                    return false;
                }

                // Filtro por Texto Predictivo (hora, docente, día, disciplina)
                if (q.length > 0) {
                    const matchHora = t.horario_str.toLowerCase().includes(q);
                    const matchProf = t.instructor_nombre.toLowerCase().includes(q);
                    const matchDias = t.dias.toLowerCase().includes(q);
                    const matchDisc = t.disciplina_nombre.toLowerCase().includes(q);
                    if (!matchHora && !matchProf && !matchDias && !matchDisc) {
                        return false;
                    }
                }

                return true;
            });
        },

        get alumnosFiltradosEnModal() {
            if (!this.turnoSeleccionado || !this.turnoSeleccionado.alumnos) return [];
            let list = this.turnoSeleccionado.alumnos;

            // Filtro por pestaña (Activos vs Ex-alumnos vs Todos)
            if (this.subfiltroModal === 'ACTIVOS') {
                list = list.filter(a => a.es_activo);
            } else if (this.subfiltroModal === 'EX_ALUMNOS') {
                list = list.filter(a => !a.es_activo);
            }

            const q = this.busquedaAlumnoModal.toLowerCase().trim();
            if (!q) return list;

            return list.filter(a => {
                return a.nombre.toLowerCase().includes(q) || a.ci.toLowerCase().includes(q) || a.factura.toLowerCase().includes(q);
            });
        },

        abrirListaAlumnos(turno) {
            this.turnoSeleccionado = turno;
            this.busquedaAlumnoModal = '';
            // Si tiene activos, abrir por defecto en activos; sino en todos
            this.subfiltroModal = (turno.total_activos > 0) ? 'ACTIVOS' : 'TODOS';
            this.modalAlumnos = true;
        },

        contarPorEstado(estado) {
            if (estado === 'DISPONIBLE') {
                return this.todos.filter(t => t.cupos_libres > 0).length;
            }
            if (estado === 'LLENO') {
                return this.todos.filter(t => t.cupos_libres === 0).length;
            }
            return this.todos.length;
        },

        resetearFiltros() {
            this.criterio = '';
            this.filtroDisciplina = 'TODAS';
            this.filtroEstado = 'TODOS';
        }
    }
}
</script>
@endsection
