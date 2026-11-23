@extends('templates.erp-layout', ['title' => 'Tarjeta de Control de Asistencia'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Barra de Acciones de Impresión (se oculta al imprimir) -->
    <div class="no-print bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Emisión Exitosa</span>
            <h2 class="text-xl font-black text-slate-900 dark:text-white">Tarjeta de Asistencia y QR Institucional</h2>
            <p class="text-xs text-slate-500">Folio: <strong>{{ $tarjeta->codigo_tarjeta }}</strong> | Recibo: <strong>{{ $tarjeta->inscripcion->pago?->numero_factura ?? 'S/F' }}</strong></p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto">
            <a href="{{ route('cajas.ventanilla') }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-300 transition-colors text-center flex-1 sm:flex-initial">
                ← Nueva Inscripción
            </a>
            <button 
                type="button" 
                onclick="window.print()" 
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#FF0066] hover:bg-[#E6005C] text-white font-black text-sm shadow-lg shadow-[#FF0066]/30 transition-all active:scale-[0.98] flex-1 sm:flex-initial"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Imprimir Tarjeta</span>
            </button>
        </div>
    </div>

    <!-- Área de Tarjeta Física (Imprimible) -->
    <div class="print:m-0 flex flex-col md:flex-row items-center justify-center gap-8 py-6">
        <!-- Cara 1: Frontal -->
        <div class="flex flex-col items-center">
            <span class="no-print text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Cara 1 (Frontal)</span>
            @include('organisms.card-front', ['tarjeta' => $tarjeta])
        </div>

        <!-- Cara 2: Posterior con QR -->
        <div class="flex flex-col items-center">
            <span class="no-print text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Cara 2 (Reverso / QR & Casillas)</span>
            @include('organisms.card-back', ['tarjeta' => $tarjeta, 'qrSvg' => $qrSvg])
        </div>
    </div>

    <!-- Resumen de Auditoría (no imprimible) -->
    <div class="no-print bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-3">Detalle Financiero Registrado</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
            <div>
                <span class="text-slate-400 block">Importe Base:</span>
                <span class="font-bold text-slate-800 dark:text-white">Bs. {{ number_format($tarjeta->inscripcion->pago?->importe_base ?? 0, 2) }}</span>
            </div>
            <div>
                <span class="text-slate-400 block">Descuento:</span>
                <span class="font-bold text-emerald-600">Bs. {{ number_format($tarjeta->inscripcion->pago?->monto_descuento ?? 0, 2) }} ({{ $tarjeta->inscripcion->pago?->porcentaje_descuento ?? 0 }}%)</span>
            </div>
            <div>
                <span class="text-slate-400 block">Total Pagado:</span>
                <span class="font-black text-[#FF0066] text-sm">Bs. {{ number_format($tarjeta->inscripcion->pago?->importe_final ?? 0, 2) }}</span>
            </div>
            <div>
                <span class="text-slate-400 block">Cajero:</span>
                <span class="font-bold text-slate-800 dark:text-white">{{ $tarjeta->inscripcion->pago?->cajero?->name ?? 'Sistema' }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
