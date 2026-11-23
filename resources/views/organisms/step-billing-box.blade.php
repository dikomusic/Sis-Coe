<div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
    <div class="mb-4">
        <span class="text-xs font-black uppercase tracking-wider text-[#FF0066]">Paso 3</span>
        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Facturación y Emisión de Tarjeta</h3>
    </div>

    <div class="space-y-4">
        <!-- Selector de Tarifa y Fecha Inicio -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                    Tarifa Aplicable <span class="text-rose-500">*</span>
                </label>
                <select 
                    name="tarifa_id" 
                    x-model="tarifaId" 
                    @change="actualizarMontoTarifa()"
                    required
                    class="block w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 sm:text-sm py-2.5 px-3 focus:border-[#FF0066] focus:ring-[#FF0066]"
                >
                    <option value="">-- Seleccionar Tarifa --</option>
                    @foreach($tarifas as $tarifa)
                        <option value="{{ $tarifa->id }}" data-costo="{{ $tarifa->costo }}">
                            {{ $tarifa->nombre }} - Bs. {{ number_format($tarifa->costo, 2) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                    Fecha de Inicio de Clases <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="date" 
                    name="fecha_inicio" 
                    x-model="fechaInicio"
                    required
                    class="block w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 sm:text-sm py-2.5 px-3 focus:border-[#FF0066] focus:ring-[#FF0066]"
                >
            </div>
        </div>

        <!-- Número de Factura / Recibo -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                    N° Factura o Recibo Oficial <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="numero_factura" 
                    x-model="numeroFactura"
                    required
                    class="block w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 sm:text-sm py-2.5 px-3 focus:border-[#FF0066] focus:ring-[#FF0066] uppercase font-mono font-bold"
                >
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                    Descuento Especial (%)
                </label>
                <input 
                    type="number" 
                    name="descuento_porcentaje" 
                    x-model="descuentoPorcentaje"
                    @input="recalcularTotales()"
                    min="0" 
                    max="100" 
                    step="5"
                    class="block w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 sm:text-sm py-2.5 px-3 focus:border-[#FF0066] focus:ring-[#FF0066]"
                >
            </div>
        </div>

        <!-- Campos de Justificación si hay descuento -->
        <template x-if="descuentoPorcentaje > 0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800">
                <div>
                    <label class="block text-xs font-bold text-amber-900 dark:text-amber-300 uppercase mb-1">
                        Motivo del Descuento <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="motivo_descuento" 
                        x-model="motivoDescuento" 
                        placeholder="Ej. Beca deportiva, Hijo de Socio, Convenio militar"
                        class="block w-full rounded-lg border-amber-300 dark:border-amber-700 sm:text-sm py-2 px-3"
                    >
                </div>
                <div>
                    <label class="block text-xs font-bold text-amber-900 dark:text-amber-300 uppercase mb-1">
                        Autorizado Por / Hoja de Ruta
                    </label>
                    <input 
                        type="text" 
                        name="autorizacion_descuento" 
                        x-model="autorizacionDescuento" 
                        placeholder="Ej. Dir. Gral. COE / HR-124/2026"
                        class="block w-full rounded-lg border-amber-300 dark:border-amber-700 sm:text-sm py-2 px-3"
                    >
                </div>
            </div>
        </template>

        <!-- Alerta y Toggle de Exoneración por Tarjeta Física Vigente (Regla COE) -->
        <template x-if="personaSeleccionada?.tarjeta_activa">
            <div class="p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-300 dark:border-emerald-700/60 flex items-center justify-between gap-3">
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input 
                        type="checkbox" 
                        x-model="tarjetaVigenteExonerada" 
                        @change="toggleExoneracionTarjetaVigente()" 
                        class="rounded border-emerald-400 text-emerald-600 focus:ring-emerald-500 h-4 w-4"
                    >
                    <div>
                        <span class="text-xs font-bold text-emerald-900 dark:text-emerald-200">
                            Tarjeta Físicamente Vigente (N° <span class="font-mono font-black" x-text="personaSeleccionada.tarjeta_codigo"></span>).
                        </span>
                        <span class="block text-[11px] text-emerald-700 dark:text-emerald-300">
                            Regla COE: Exonerar cobro de renovación a Bs. 0.00 mientras siga vigente.
                        </span>
                    </div>
                </label>
                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-emerald-200 dark:bg-emerald-800 text-emerald-900 dark:text-emerald-100">
                    Regla COE
                </span>
            </div>
        </template>

        <!-- Hidden inputs con valores finales -->
        <input type="hidden" name="importe_base" :value="importeBase">
        <input type="hidden" name="importe_final" :value="importeFinal">
        <input type="hidden" name="tipo_beneficio" :value="tipoBeneficio">

        <!-- Molécula de Desglose de Pago -->
        @include('molecules.invoice-summary-row')

        <!-- Botones de Acción: POS Rápido Individual y por Lote Familiar -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
            <!-- Botón 1: Agregar a la Cuenta Familiar / Lote -->
            <button 
                type="button" 
                @click="agregarAlCarrito()"
                :disabled="!itemValidoParaCarrito"
                class="w-full py-3.5 px-4 rounded-xl font-black text-xs shadow-md transition-all flex items-center justify-center gap-2 border active:scale-[0.99]"
                :class="itemValidoParaCarrito 
                    ? 'bg-slate-900 hover:bg-slate-800 text-white border-slate-900 cursor-pointer shadow-slate-900/20' 
                    : 'bg-slate-100 dark:bg-slate-800 text-slate-400 border-slate-200 dark:border-slate-700 cursor-not-allowed shadow-none'"
            >
                <svg class="h-4 w-4 text-[#EDD00A]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span>+ Agregar a Lote Familiar / Cola</span>
            </button>

            <!-- Botón 2: Cobro Directo Inmediato (1 solo alumno) -->
            <button 
                type="submit" 
                :disabled="!formularioValido"
                class="w-full py-3.5 px-4 rounded-xl font-black text-white text-xs shadow-md transition-all flex items-center justify-center gap-2 active:scale-[0.99]"
                :class="formularioValido 
                    ? 'bg-[#FF0066] hover:bg-[#E6005C] shadow-[#FF0066]/30 cursor-pointer' 
                    : 'bg-slate-200 dark:bg-slate-800 text-slate-400 cursor-not-allowed shadow-none'"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Emitir Cobro Directo (1 Alumno)</span>
            </button>
        </div>

        <!-- Indicador de pasos pendientes para el cajero (UX) -->
        <template x-if="!formularioValido">
            <div class="p-2.5 rounded-lg bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 text-xs text-amber-800 dark:text-amber-300 flex items-center gap-2">
                <svg class="h-4 w-4 shrink-0 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>
                    <strong>Para habilitar el cobro:</strong>
                    <template x-if="!personaSeleccionada"> Seleccione una persona en el Paso 1.</template>
                    <template x-if="personaSeleccionada && !horarioSeleccionado"> Seleccione un turno disponible en el Paso 2.</template>
                    <template x-if="personaSeleccionada && horarioSeleccionado && !tarifaId"> Seleccione una tarifa en el Paso 3.</template>
                    <template x-if="personaSeleccionada && horarioSeleccionado && tarifaId && !numeroFactura.trim()"> Ingrese el N° de Factura/Recibo.</template>
                </span>
            </div>
        </template>
    </div>
</div>
