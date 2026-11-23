@extends('templates.erp-layout', ['title' => 'Módulo de Consultas y Verificación'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="border-b border-slate-200 dark:border-slate-800 pb-4">
        <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Centro de Verificación y Consultas</h2>
        <p class="text-xs text-slate-500 mt-0.5">Control de acceso inmediato, validación de credenciales y búsqueda unificada de membresías COE.</p>
    </div>

    <!-- Buscador Rápido por Código de Credencial o Escáner QR -->
    <div class="rounded-2xl border-2 border-blue-600 bg-white dark:bg-slate-900 p-6 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 block mb-1">Control de Acceso Inmediato</span>
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Verificar Credencial o Código QR</h3>

        <form action="{{ route('consultas.credencial.buscar') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    name="codigo" 
                    placeholder="Escanear código QR o ingresar N° de credencial..."
                    autofocus
                    required
                    class="block w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 py-3 pl-11 pr-4 text-sm font-mono text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20"
                >
            </div>
            <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/25 transition-all">
                Verificar Acceso
            </button>
        </form>
    </div>

    <!-- Modos de Búsqueda Avanzada -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <a href="{{ route('consultas.search', ['tipo' => 'SOCIO', 'mode' => 'TODOS']) }}" class="group rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm hover:border-blue-500 hover:shadow-md transition-all">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white flex items-center justify-center transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-base text-slate-900 dark:text-white group-hover:text-blue-600 transition-colors">Consulta de Socios Titulares</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Búsqueda por nombres, apellidos, CI, grado o boleta de pago.</p>
                </div>
            </div>
        </a>

        <a href="{{ route('consultas.search', ['tipo' => 'BENEFICIARIO', 'mode' => 'TODOS']) }}" class="group rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm hover:border-emerald-500 hover:shadow-md transition-all">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-base text-slate-900 dark:text-white group-hover:text-emerald-600 transition-colors">Consulta de Beneficiarios</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Familiares con carnet vigente y relación de parentesco militar.</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
