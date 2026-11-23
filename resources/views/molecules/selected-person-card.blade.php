@props([
    'persona' => null,
])

<div class="rounded-2xl border-2 border-[#FF0066] bg-[#FF0066]/5 dark:bg-[#FF0066]/10 p-4 transition-all" x-show="personaSeleccionada" x-cloak>
    <div class="flex items-start justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="h-12 w-12 rounded-xl bg-gradient-to-tr from-[#FF0066] to-[#C91459] flex items-center justify-center text-white font-black text-lg shadow-md shadow-[#FF0066]/25 flex-shrink-0">
                <span x-text="personaSeleccionada ? personaSeleccionada.nombre_completo.charAt(0) : 'P'"></span>
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h4 class="font-bold text-slate-900 dark:text-white text-base" x-text="personaSeleccionada?.nombre_completo"></h4>
                    <span 
                        class="px-2 py-0.5 text-xs font-semibold rounded-full"
                        :class="{
                            'bg-blue-100 text-blue-800 border border-blue-300 dark:bg-blue-900/60 dark:text-blue-300': personaSeleccionada?.tipo_persona === 'SOCIO',
                            'bg-emerald-100 text-emerald-800 border border-emerald-300 dark:bg-emerald-900/60 dark:text-emerald-300': personaSeleccionada?.tipo_persona === 'BENEFICIARIO',
                            'bg-amber-100 text-amber-800 border border-amber-300 dark:bg-amber-900/60 dark:text-amber-300': personaSeleccionada?.tipo_persona === 'PARTICULAR'
                        }"
                        x-text="personaSeleccionada?.tipo_persona"
                    ></span>
                </div>
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1 text-xs text-slate-600 dark:text-slate-400">
                    <span><strong>CI:</strong> <span x-text="personaSeleccionada?.ci"></span></span>
                    <template x-if="personaSeleccionada?.grado">
                        <span>• <strong>Grado:</strong> <span x-text="personaSeleccionada?.grado"></span></span>
                    </template>
                    <template x-if="personaSeleccionada?.telefono">
                        <span>• <strong>Tel:</strong> <span x-text="personaSeleccionada?.telefono"></span></span>
                    </template>
                </div>
            </div>
        </div>

        <button 
            type="button" 
            @click="deseleccionarPersona()" 
            class="rounded-lg p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors"
            title="Cambiar persona"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Alerta de Tarjeta Vigente (Regla COE: Renovación no se cobra si tarjeta física sigue vigente) -->
    <template x-if="personaSeleccionada?.tarjeta_activa">
        <div class="mt-3 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-300 dark:border-emerald-700/60 flex items-start justify-between gap-3">
            <div class="flex items-start gap-2.5">
                <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-black text-emerald-900 dark:text-emerald-200 text-xs uppercase tracking-wide">
                            Tarjeta Físicamente Vigente
                        </span>
                        <span class="font-mono text-[11px] font-bold px-1.5 py-0.5 bg-emerald-100 dark:bg-emerald-900 text-emerald-800 dark:text-emerald-200 rounded">
                            <span x-text="personaSeleccionada.tarjeta_codigo"></span>
                        </span>
                    </div>
                    <p class="text-xs text-emerald-700 dark:text-emerald-300 mt-0.5">
                        Vence: <strong x-text="personaSeleccionada.tarjeta_vence"></strong> (<span x-text="personaSeleccionada.dias_restantes"></span> días restantes).
                        <span class="block mt-0.5 text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold">
                            Regla COE: La renovación no se cobra mientras la tarjeta física esté vigente.
                        </span>
                    </p>
                </div>
            </div>

            <button 
                type="button" 
                @click="aplicarExoneracionTarjetaVigente()" 
                class="shrink-0 px-2.5 py-1 rounded-lg text-xs font-black transition-all shadow-sm"
                :class="tarjetaVigenteExonerada 
                    ? 'bg-emerald-600 text-white' 
                    : 'bg-emerald-100 hover:bg-emerald-200 text-emerald-900 dark:bg-emerald-900 dark:text-emerald-100'"
            >
                <span x-text="tarjetaVigenteExonerada ? 'Exonerado (Bs. 0.00)' : 'Exonerar Cobro'"></span>
            </button>
        </div>
    </template>

    <!-- Acceso de Renovación Rápida con 1 Clic -->
    <template x-if="personaSeleccionada?.ultimo_curso">
        <div class="mt-3 pt-2.5 border-t border-[#FF0066]/20 flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-1.5 text-xs text-slate-700 dark:text-slate-300">
                <svg class="h-4 w-4 text-[#FF0066]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span><strong>Último curso registrado:</strong> <span class="text-[#FF0066] font-bold" x-text="personaSeleccionada.ultimo_curso.disciplina_nombre"></span></span>
            </div>
            <button 
                type="button" 
                @click="renovarUltimoCurso()" 
                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-[#EDD00A] hover:bg-[#ffe01a] text-slate-900 font-black text-xs shadow-sm transition-all active:scale-95"
            >
                <svg class="h-3.5 w-3.5 text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <span>Auto-completar Renovación</span>
            </button>
        </div>
    </template>
</div>
