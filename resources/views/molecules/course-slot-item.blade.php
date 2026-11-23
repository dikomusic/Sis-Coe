<div 
    class="relative cursor-pointer rounded-xl border p-3.5 transition-all"
    :class="horarioSeleccionado?.id === horario.id 
        ? 'border-[#FF0066] bg-[#FF0066]/10 dark:bg-[#FF0066]/20 ring-2 ring-[#FF0066]/30' 
        : ((horario.cupos_disponibles ?? horario.cupos_libres ?? 0) <= 0 ? 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 opacity-60 cursor-not-allowed' : 'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-slate-300 dark:hover:border-slate-700')"
    @click="((horario.cupos_disponibles ?? horario.cupos_libres ?? 0) > 0) && seleccionarHorario(horario)"
>
    <div class="flex items-start justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="font-bold text-slate-900 dark:text-white text-sm" x-text="horario.dias"></span>
                <span class="rounded bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 text-xs font-mono font-bold text-slate-700 dark:text-slate-300" x-text="horario.rango_horario"></span>
            </div>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                Instructor: <strong class="text-slate-700 dark:text-slate-300" x-text="horario.instructor"></strong>
            </p>
        </div>

        <div class="text-right">
            <span 
                class="inline-block px-2 py-0.5 text-xs font-bold rounded-full"
                :class="(horario.cupos_disponibles ?? horario.cupos_libres ?? 0) > 5 
                    ? 'bg-emerald-100 text-emerald-800 border border-emerald-300 dark:bg-emerald-950 dark:text-emerald-300' 
                    : ((horario.cupos_disponibles ?? horario.cupos_libres ?? 0) > 0 
                        ? 'bg-amber-100 text-amber-800 border border-amber-300 dark:bg-amber-950 dark:text-amber-300' 
                        : 'bg-rose-100 text-rose-800 border border-rose-300 dark:bg-rose-950 dark:text-rose-300')"
                x-text="(horario.cupos_disponibles ?? horario.cupos_libres ?? 0) > 0 ? ((horario.cupos_disponibles ?? horario.cupos_libres ?? 0) + ' cupos') : 'Agotado'"
            ></span>
        </div>
    </div>
</div>
