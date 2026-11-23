<div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 p-4 space-y-2.5">
    <div class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-400">
        <span>Importe Base Tarifa:</span>
        <span class="font-semibold text-slate-900 dark:text-white" x-text="'Bs. ' + Number(importeBase).toFixed(2)"></span>
    </div>

    <template x-if="descuentoPorcentaje > 0">
        <div class="flex items-center justify-between text-sm text-emerald-600 dark:text-emerald-400 font-medium">
            <span>Descuento aplicado (<span x-text="descuentoPorcentaje"></span>%):</span>
            <span x-text="'- Bs. ' + Number(montoDescuento).toFixed(2)"></span>
        </div>
    </template>

    <div class="border-t border-slate-200 dark:border-slate-700 pt-2.5 flex items-center justify-between">
        <span class="font-bold text-slate-900 dark:text-white text-base">Total a Cobrar:</span>
        <span class="text-2xl font-black text-[#FF0066]" x-text="'Bs. ' + Number(importeFinal).toFixed(2)"></span>
    </div>
</div>
