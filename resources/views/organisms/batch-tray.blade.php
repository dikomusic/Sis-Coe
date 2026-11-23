<!-- Bandeja de Cobro Familiar / Lote (Multipersona) -->
<div 
    x-show="carrito.length > 0" 
    x-transition
    class="rounded-2xl border-2 border-[#FF0066] bg-white dark:bg-slate-900 shadow-xl overflow-hidden"
    x-cloak
>
    <!-- Encabezado de la Bandeja -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 px-6 py-4 flex flex-wrap items-center justify-between gap-3 text-white">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-[#FF0066] text-white flex items-center justify-center font-black shadow-md shadow-[#FF0066]/30">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <h3 class="font-black text-base tracking-tight">Bandeja de Cobro Familiar / Lote</h3>
                <p class="text-xs text-slate-300">
                    <span class="font-bold text-[#EDD00A]" x-text="carrito.length"></span> alumno(s) agregados para emitir en un solo pago.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="text-right">
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Gran Total a Cobrar</span>
                <span class="text-xl font-black text-[#EDD00A] font-mono">
                    Bs. <span x-text="totalLote.toFixed(2)"></span>
                </span>
            </div>

            <button 
                type="button" 
                @click="vaciarCarrito()" 
                class="px-3 py-1.5 rounded-lg text-xs font-bold text-rose-300 hover:text-white hover:bg-rose-950/60 transition-colors"
                title="Vaciar la bandeja"
            >
                Vaciar
            </button>
        </div>
    </div>

    <!-- Lista de Alumnos en la Bandeja -->
    <div class="overflow-x-auto divide-y divide-slate-100 dark:divide-slate-800">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 dark:bg-slate-800/60 text-[10px] uppercase tracking-wider font-bold text-slate-500 border-b border-slate-200 dark:border-slate-800">
                <tr>
                    <th class="px-4 py-2.5">#</th>
                    <th class="px-4 py-2.5">Alumno</th>
                    <th class="px-4 py-2.5">Disciplina & Turno</th>
                    <th class="px-4 py-2.5">Tarifa</th>
                    <th class="px-4 py-2.5 text-right">Subtotal</th>
                    <th class="px-4 py-2.5 text-center">Quitar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                <template x-for="(item, idx) in carrito" :key="item.id_temp">
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40">
                        <td class="px-4 py-3 font-mono font-bold text-slate-400" x-text="idx + 1"></td>
                        <td class="px-4 py-3">
                            <p class="font-bold text-slate-900 dark:text-white text-xs" x-text="item.persona_nombre"></p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                CI: <span class="font-mono" x-text="item.persona_ci"></span> • 
                                <span class="font-semibold uppercase" x-text="item.persona_tipo"></span>
                            </p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-black bg-[#FF0066]/10 text-[#FF0066] border border-[#FF0066]/20" x-text="item.disciplina_nombre"></span>
                            <p class="text-[11px] text-slate-600 dark:text-slate-300 font-medium mt-0.5" x-text="item.horario_texto"></p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs text-slate-700 dark:text-slate-300 font-semibold" x-text="item.tarifa_nombre"></span>
                            <template x-if="item.descuento_porcentaje > 0">
                                <span class="text-[10px] text-emerald-600 dark:text-emerald-400 block font-bold" x-text="'-' + item.descuento_porcentaje + '% Desc.'"></span>
                            </template>
                        </td>
                        <td class="px-4 py-3 text-right font-mono font-black text-slate-900 dark:text-white">
                            Bs. <span x-text="item.importe_final.toFixed(2)"></span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button 
                                type="button" 
                                @click="quitarDelCarrito(idx)" 
                                class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Barra de Cobro del Lote -->
    <div class="p-4 bg-slate-50 dark:bg-slate-800/60 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 whitespace-nowrap">
                N° Factura / Recibo:
            </label>
            <input 
                type="text" 
                x-model="numeroFactura" 
                placeholder="N° Comprobante" 
                class="rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-mono font-bold py-2 px-3 focus:border-[#FF0066] focus:ring-[#FF0066] w-full sm:w-40"
            >
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
            <button 
                type="button" 
                @click="cobrarLote()" 
                :disabled="cobrandoLote || !numeroFactura.trim() || carrito.length === 0"
                class="w-full sm:w-auto px-6 py-2.5 rounded-xl font-black text-white text-xs shadow-lg transition-all flex items-center justify-center gap-2"
                :class="!cobrandoLote && numeroFactura.trim() && carrito.length > 0 
                    ? 'bg-[#FF0066] hover:bg-[#E6005C] shadow-[#FF0066]/30 cursor-pointer active:scale-98' 
                    : 'bg-slate-300 dark:bg-slate-800 text-slate-400 cursor-not-allowed shadow-none'"
            >
                <template x-if="cobrandoLote">
                    @include('atoms.spinner', ['size' => 'sm', 'color' => 'white'])
                </template>
                <template x-if="!cobrandoLote">
                    <svg class="h-4 w-4 text-[#EDD00A]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </template>
                <span>Confirmar y Cobrar Lote Familiar (<span x-text="carrito.length"></span> Alumnos - Bs. <span x-text="totalLote.toFixed(2)"></span>)</span>
            </button>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Cobro Familiar Exitoso -->
<div 
    x-show="modalLoteExitoso" 
    class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
    x-cloak
>
    <div 
        @click.away="cerrarModalLoteExitoso()" 
        class="bg-white dark:bg-slate-900 rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-4"
    >
        <div class="text-center space-y-2">
            <div class="h-14 w-14 rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400 mx-auto flex items-center justify-center">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 class="text-lg font-black text-slate-900 dark:text-white">¡Cobro Familiar Exitoso!</h3>
            <p class="text-xs text-slate-500">
                Se registraron <strong x-text="loteResultado?.total_inscripciones"></strong> inscripciones bajo el comprobante 
                <strong class="font-mono text-[#FF0066]" x-text="'#' + loteResultado?.numero_factura"></strong> 
                por un total de <strong class="font-mono text-emerald-600">Bs. <span x-text="loteResultado?.total_cobrado?.toFixed(2)"></span></strong>.
            </p>
        </div>

        <!-- Tarjetas Generadas -->
        <div class="space-y-2 max-h-56 overflow-y-auto">
            <template x-for="t in loteResultado?.tarjetas" :key="t.tarjeta_id">
                <div class="p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-between gap-3">
                    <div>
                        <p class="font-bold text-xs text-slate-900 dark:text-white" x-text="t.alumno"></p>
                        <p class="text-[11px] text-slate-500">
                            <span class="text-[#FF0066] font-semibold" x-text="t.disciplina"></span> • 
                            Tarjeta: <span class="font-mono font-bold" x-text="t.codigo_tarjeta"></span>
                        </p>
                    </div>
                    <a 
                        :href="t.url" 
                        target="_blank" 
                        class="px-3 py-1 rounded-lg text-xs font-bold bg-[#FF0066] hover:bg-[#E6005C] text-white shadow-sm transition-all flex items-center gap-1"
                    >
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                        <span>Ver Tarjeta QR</span>
                    </a>
                </div>
            </template>
        </div>

        <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
            <button 
                type="button" 
                @click="cerrarModalLoteExitoso()" 
                class="w-full py-2.5 rounded-xl font-black text-xs bg-slate-900 hover:bg-slate-800 text-white transition-all shadow-md active:scale-98"
            >
                + Atender Siguiente Alumno / Familia en Fila
            </button>
        </div>
    </div>
</div>
