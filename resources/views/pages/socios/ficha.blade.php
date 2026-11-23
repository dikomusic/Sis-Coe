@extends('templates.erp-layout', ['title' => 'Ficha del Socio Titular'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Padrón de Membresías</span>
            <h2 class="text-2xl font-black text-slate-900 dark:text-white">Ficha de Registro Militar</h2>
            <p class="text-xs text-slate-500">ID Titular: #{{ $socio->id_titular }} | Boleta: {{ $socio->boleta_pago ?? 'S/B' }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.socio.index') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
                ← Volver
            </a>
            <a href="{{ route('admin.socio.ficha.pdf', $socio->id_titular) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/25 transition-all">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Descargar PDF Oficial</span>
            </a>
        </div>
    </div>

    <!-- Tarjeta Principal de Información -->
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 pb-6 border-b border-slate-100 dark:border-slate-800">
            @if($socio->foto)
                <img src="{{ asset('storage/' . $socio->foto) }}" class="h-32 w-32 rounded-2xl object-cover border-2 border-slate-200 shadow-sm">
            @else
                <div class="h-32 w-32 rounded-2xl bg-gradient-to-tr from-blue-700 to-indigo-600 text-white font-black text-4xl flex items-center justify-center shadow-md">
                    {{ strtoupper(substr($socio->nombres, 0, 1)) }}
                </div>
            @endif

            <div class="flex-1 text-center sm:text-left space-y-2">
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                        {{ $socio->grado }}
                    </span>
                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                        {{ $socio->arma_especialidad }}
                    </span>
                    <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ strtolower($socio->estado_socio) === 'activo' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                        {{ $socio->estado_socio }}
                    </span>
                </div>

                <h3 class="text-xl font-black text-slate-900 dark:text-white">
                    {{ $socio->paterno }} {{ $socio->materno }} {{ $socio->nombres }}
                </h3>

                <p class="text-xs text-slate-500">
                    CI: <strong class="text-slate-800 dark:text-slate-200 font-mono">{{ $socio->ci }} {{ $socio->expedido }}</strong>
                    • Situación: <strong class="text-slate-800 dark:text-slate-200">{{ $socio->situacion_actual }}</strong>
                    • Teléfono: <strong class="text-slate-800 dark:text-slate-200">{{ $socio->telefono ?? 'Sin registrar' }}</strong>
                </p>
            </div>
        </div>

        <!-- Beneficiarios Registrados -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-bold text-sm text-slate-800 dark:text-white uppercase tracking-wider">Beneficiarios / Familiares Directos</h4>
                <a href="{{ route('admin.socio.beneficiario.create', $socio->id_titular) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                    + Agregar Beneficiario
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @forelse($socio->beneficiarios as $ben)
                    <div class="rounded-xl border border-slate-200 dark:border-slate-800 p-3.5 flex items-center justify-between bg-slate-50/50">
                        <div>
                            <span class="font-bold text-xs text-slate-900 dark:text-white block">{{ $ben->paterno }} {{ $ben->materno }} {{ $ben->nombres }}</span>
                            <span class="text-[11px] text-slate-500">Parentesco: <strong class="text-blue-600">{{ $ben->parentesco }}</strong> • CI: {{ $ben->ci }}</span>
                        </div>
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-800">
                            Activo
                        </span>
                    </div>
                @empty
                    <div class="col-span-full py-4 text-center text-slate-400 text-xs border border-dashed rounded-xl">
                        No tiene beneficiarios registrados aún.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
