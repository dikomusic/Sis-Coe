@extends('templates.erp-layout', ['title' => 'Detalle de Consulta'])

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4">
        <div>
            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Detalle del Registro</h2>
            <p class="text-xs text-slate-500">Módulo de consultas y control de credenciales.</p>
        </div>
        <a href="{{ route('consultas.index') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
            ← Volver a Consultas
        </a>
    </div>

    @if($mensajeError)
        <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-300 text-amber-800 text-xs font-semibold">
            {{ $mensajeError }}
        </div>
    @endif

    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-4">
        <div class="flex items-center gap-4">
            @if($entidad->foto)
                <img src="{{ asset('storage/' . $entidad->foto) }}" class="h-24 w-24 rounded-2xl object-cover border">
            @else
                <div class="h-24 w-24 rounded-2xl bg-blue-100 text-blue-700 font-black text-3xl flex items-center justify-center">
                    {{ strtoupper(substr($entidad->nombres ?? 'S', 0, 1)) }}
                </div>
            @endif
            <div>
                <span class="text-xs font-bold uppercase text-blue-600 dark:text-blue-400">
                    {{ class_basename($entidad) === 'SocioTitular' ? 'SOCIO TITULAR' : ('BENEFICIARIO (' . ($entidad->parentesco ?? '') . ')') }}
                </span>
                <h3 class="text-xl font-black text-slate-900 dark:text-white">
                    {{ $entidad->grado ?? '' }} {{ $entidad->paterno ?? '' }} {{ $entidad->materno ?? '' }} {{ $entidad->nombres ?? '' }}
                </h3>
                <p class="text-xs text-slate-500">CI: <strong class="text-slate-800 dark:text-slate-200 font-mono">{{ $entidad->ci }} {{ $entidad->expedido }}</strong></p>
            </div>
        </div>

        @if($credencial)
            <div class="mt-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 grid grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-slate-400 block">Número Credencial:</span>
                    <strong class="font-mono text-sm text-slate-800 dark:text-white">#{{ $credencial->id }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block">Estado:</span>
                    <span class="px-2 py-0.5 text-[11px] font-bold rounded-full uppercase {{ $credencial->estado === 'activo' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                        {{ $credencial->estado }}
                    </span>
                </div>
                <div>
                    <span class="text-slate-400 block">Emisión:</span>
                    <strong class="text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::parse($credencial->fecha_emision)->format('d/m/Y') }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block">Vigencia hasta:</span>
                    <strong class="text-blue-600 font-bold">{{ \Carbon\Carbon::parse($credencial->fecha_vencimiento)->format('d/m/Y') }}</strong>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
