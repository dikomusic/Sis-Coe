@extends('templates.erp-layout', ['title' => 'Resultado de Verificación'])

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Control de Acceso</span>
            <h2 class="text-2xl font-black text-slate-900 dark:text-white">Estado de la Credencial</h2>
        </div>
        <a href="{{ route('consultas.index') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
            ← Nueva Consulta
        </a>
    </div>

    @if($mensajeError)
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border-2 border-rose-500 text-rose-900 dark:text-rose-200 text-center space-y-1 shadow-md">
            <div class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-rose-100 text-rose-600 mb-2">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="font-extrabold text-lg text-rose-700 dark:text-rose-400">CREDENCIAL NO HABILITADA</h3>
            <p class="text-xs font-semibold">{{ $mensajeError }}</p>
        </div>
    @else
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border-2 border-emerald-500 text-emerald-900 dark:text-emerald-200 text-center space-y-1 shadow-md">
            <div class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 text-emerald-600 mb-2">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="font-extrabold text-lg text-emerald-700 dark:text-emerald-400">ACCESO AUTORIZADO - SOCIO HABILITADO</h3>
            <p class="text-xs font-semibold">Credencial vigente en las bases de datos del COE.</p>
        </div>
    @endif

    @if($entidad)
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-4">
            <div class="flex items-center gap-4 border-b border-slate-100 dark:border-slate-800 pb-4">
                @if($entidad->foto)
                    <img src="{{ asset('storage/' . $entidad->foto) }}" class="h-20 w-20 rounded-2xl object-cover border-2 border-slate-200">
                @else
                    <div class="h-20 w-20 rounded-2xl bg-blue-100 text-blue-700 font-bold text-2xl flex items-center justify-center">
                        {{ strtoupper(substr($entidad->nombres ?? 'S', 0, 1)) }}
                    </div>
                @endif
                <div>
                    <span class="text-xs font-bold uppercase text-blue-600 dark:text-blue-400">
                        {{ class_basename($entidad) === 'SocioTitular' ? 'SOCIO TITULAR' : ('BENEFICIARIO (' . ($entidad->parentesco ?? '') . ')') }}
                    </span>
                    <h3 class="text-lg font-black text-slate-900 dark:text-white leading-tight">
                        {{ $entidad->grado ?? '' }} {{ $entidad->paterno ?? '' }} {{ $entidad->materno ?? '' }} {{ $entidad->nombres ?? '' }}
                    </h3>
                    <p class="text-xs text-slate-500 font-mono">CI: {{ $entidad->ci ?? 'S/C' }} {{ $entidad->expedido ?? '' }}</p>
                </div>
            </div>

            @if($credencial)
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 block font-semibold">Fecha de Emisión:</span>
                        <strong class="text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::parse($credencial->fecha_emision)->format('d/m/Y') }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-semibold">Fecha de Vencimiento:</span>
                        <strong class="text-blue-600 dark:text-blue-400 font-bold">{{ \Carbon\Carbon::parse($credencial->fecha_vencimiento)->format('d/m/Y') }}</strong>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
