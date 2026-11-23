<div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
    <div class="mb-4">
        <span class="text-xs font-black uppercase tracking-wider text-[#FF0066]">Paso 2</span>
        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Seleccionar Disciplina y Turno de Horario</h3>
    </div>

    <!-- Pestañas de Disciplinas -->
    <div class="flex flex-wrap gap-2 mb-5">
        @foreach($disciplinas as $disciplina)
            <button 
                type="button" 
                @click="seleccionarDisciplina({{ $disciplina->id }}, '{{ addslashes($disciplina->nombre) }}')"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all"
                :class="disciplinaSeleccionadaId === {{ $disciplina->id }} 
                    ? 'bg-[#FF0066] text-white shadow-md shadow-[#FF0066]/30' 
                    : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
            >
                {{ $disciplina->nombre }}
            </button>
        @endforeach
    </div>

    <!-- Grilla de Horarios de la Disciplina seleccionada -->
    <template x-if="cargandoHorarios">
        <div class="py-8 text-center text-slate-400">
            @include('atoms.spinner', ['size' => 'md', 'color' => 'blue'])
            <p class="mt-2 text-xs">Cargando turnos y cupos disponibles...</p>
        </div>
    </template>

    <template x-if="!cargandoHorarios && horariosDisponibles.length > 0">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <template x-for="horario in horariosDisponibles" :key="horario.id">
                @include('molecules.course-slot-item')
            </template>
        </div>
    </template>

    <template x-if="!cargandoHorarios && horariosDisponibles.length === 0 && disciplinaSeleccionadaId">
        <div class="py-6 text-center text-slate-400 dark:text-slate-500 border border-dashed rounded-xl">
            <p class="text-sm font-medium">No hay turnos disponibles para esta disciplina actualmente.</p>
        </div>
    </template>

    <input type="hidden" name="horario_curso_id" :value="horarioSeleccionado ? horarioSeleccionado.id : ''">
</div>
