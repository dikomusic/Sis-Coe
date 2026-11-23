@props([
    'tarjeta' => null,
    'qrSvg' => null,
])

<!-- Cara Posterior (Reverso) -->
<div class="w-[88mm] h-[56mm] bg-white text-slate-900 rounded-2xl p-3 shadow-xl border border-slate-300 relative overflow-hidden flex flex-col justify-between print:shadow-none print:border-black print:rounded-none">
    <div class="flex items-center justify-between border-b border-slate-200 pb-1">
        <span class="text-[8px] font-black uppercase tracking-wider text-slate-700">Control de Asistencia Oficial (12 Clases)</span>
        <span class="text-[7.5px] font-mono font-bold text-slate-500">ESCÁNER QR / FIRMA</span>
    </div>

    <!-- Contenido Central: Grilla de 12 casillas + QR Code -->
    <div class="flex items-center gap-2.5 my-auto">
        <!-- Grilla de 12 Casillas (4 columnas x 3 filas) -->
        <div class="flex-1 grid grid-cols-4 gap-1">
            @for($i = 1; $i <= 12; $i++)
                <div class="h-8 border border-slate-400 rounded flex flex-col items-center justify-between p-0.5 bg-slate-50/50">
                    <span class="text-[6.5px] font-bold text-slate-600 leading-none">C-{{ $i }}</span>
                    <span class="text-[6px] text-slate-300 font-mono">____</span>
                </div>
            @endfor
        </div>

        <!-- Bloque de Código QR Institucional -->
        <div class="w-20 flex flex-col items-center justify-center p-1 border border-slate-200 rounded-lg bg-slate-50">
            <div class="w-16 h-16 flex items-center justify-center">
                {!! $qrSvg !!}
            </div>
            <span class="text-[6px] font-mono font-bold text-slate-600 mt-0.5 tracking-tighter">ID: {{ substr($tarjeta->codigo_qr, 0, 8) }}</span>
        </div>
    </div>

    <!-- Pie: Normativa Institucional -->
    <div class="border-t border-slate-200 pt-1 flex items-center justify-between text-[6.5px] text-slate-500 leading-tight">
        <p class="max-w-[190px]">Esta tarjeta es personal e intransferible. Obligatoria para el ingreso a instalaciones del COE.</p>
        <div class="text-right">
            <span class="font-bold uppercase text-slate-700 block">Firma Cajero / Sello</span>
        </div>
    </div>
</div>
