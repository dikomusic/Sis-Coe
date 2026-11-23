<div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <div>
            <span class="text-xs font-black uppercase tracking-wider text-[#FF0066]">Paso 1</span>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Identificar Socio, Beneficiario o Particular</h3>
        </div>

        <button 
            type="button" 
            @click="abrirModalParticular = true"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 transition-colors"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            <span>+ Nuevo Particular</span>
        </button>
    </div>

    <!-- Si ya seleccionó una persona -->
    <template x-if="personaSeleccionada">
        <div>
            @include('molecules.selected-person-card')
            <input type="hidden" name="persona_id" :value="personaSeleccionada.id">
        </div>
    </template>

    <!-- Si aún no ha seleccionado -->
    <template x-if="!personaSeleccionada">
        <div class="relative">
            @include('molecules.search-input')

            <!-- Dropdown de resultados predictivos -->
            <div 
                x-show="resultados.length > 0" 
                @click.away="resultados = []"
                class="absolute z-20 mt-1 max-h-60 w-full overflow-auto rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-xl"
                x-cloak
            >
                <ul class="divide-y divide-slate-100 dark:divide-slate-800">
                    <template x-for="item in resultados" :key="item.id">
                        <li 
                            @click="seleccionarPersona(item)"
                            class="cursor-pointer p-3.5 hover:bg-slate-50 dark:hover:bg-slate-800/70 transition-colors flex items-center justify-between"
                        >
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-lg bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 font-bold flex items-center justify-center text-sm">
                                    <span x-text="item.nombre_completo.charAt(0)"></span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 dark:text-white" x-text="item.nombre_completo"></p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        CI: <span class="font-mono" x-text="item.ci"></span>
                                        <template x-if="item.grado">
                                            <span> • Grado: <span x-text="item.grado"></span></span>
                                        </template>
                                    </p>
                                </div>
                            </div>

                            <span 
                                class="px-2 py-0.5 text-[11px] font-bold rounded-full uppercase"
                                :class="{
                                    'bg-blue-100 text-blue-800 dark:bg-blue-900/60 dark:text-blue-300': item.tipo_persona === 'SOCIO',
                                    'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300': item.tipo_persona === 'BENEFICIARIO',
                                    'bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-300': item.tipo_persona === 'PARTICULAR'
                                }"
                                x-text="item.tipo_persona"
                            ></span>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </template>
</div>
