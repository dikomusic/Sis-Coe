@extends('templates.erp-layout', ['title' => 'Panel de Control General'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Panel Gerencial</span>
            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Bienvenido al ERP SISCOE</h2>
            <p class="text-xs text-slate-500 mt-0.5">Control integrado de Membresías, Cajas, Credenciales y Accesos del Club.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('cajas.ventanilla') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/25 transition-all">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Ventanilla Cajas</span>
            </a>
            <a href="{{ route('admin.socio.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs transition-all">
                + Nuevo Socio
            </a>
        </div>
    </div>

    <!-- Organismo: KPIs Globales del ERP -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @include('molecules.stat-card', [
            'title' => 'Socios Titulares',
            'value' => number_format($stats['total_socios']),
            'icon' => 'users',
            'color' => 'blue',
            'subtitle' => 'Oficiales registrados'
        ])

        @include('molecules.stat-card', [
            'title' => 'Beneficiarios',
            'value' => number_format($stats['total_beneficiarios']),
            'icon' => 'users',
            'color' => 'emerald',
            'subtitle' => 'Familiares activos'
        ])

        @include('molecules.stat-card', [
            'title' => 'Recaudado Hoy (Cajas)',
            'value' => 'Bs. ' . number_format($stats['recaudacion_hoy'], 2),
            'icon' => 'currency',
            'color' => 'amber',
            'subtitle' => $stats['inscripciones_hoy'] . ' cobros emitidos'
        ])

        @include('molecules.stat-card', [
            'title' => 'Credenciales Activas',
            'value' => number_format($stats['credenciales_activas']),
            'icon' => 'shield',
            'color' => 'purple',
            'subtitle' => 'Pases y carnets COE'
        ])
    </div>

    <!-- Accesos Directos a Módulos -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="{{ route('admin.socio.index') }}" class="group rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm hover:border-blue-500 hover:shadow-md transition-all">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-slate-800 dark:text-white group-hover:text-blue-600">Padrón de Socios</h4>
                    <p class="text-xs text-slate-500">Gestión de miembros y grados</p>
                </div>
            </div>
        </a>

        <a href="{{ route('cajas.index') }}" class="group rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm hover:border-emerald-500 hover:shadow-md transition-all">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-slate-800 dark:text-white group-hover:text-emerald-600">Módulo de Cajas</h4>
                    <p class="text-xs text-slate-500">Inscripciones, turnos y arqueos</p>
                </div>
            </div>
        </a>

        <a href="{{ route('consultas.index') }}" class="group rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm hover:border-purple-500 hover:shadow-md transition-all">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-slate-800 dark:text-white group-hover:text-purple-600">Verificación & QR</h4>
                    <p class="text-xs text-slate-500">Escanear pases y credenciales</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Tablas de Actividad Reciente -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Últimos Socios Registrados -->
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 dark:text-white text-sm uppercase tracking-wider">Últimos Socios Incorporados</h3>
                <a href="{{ route('admin.socio.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">Ver todos →</a>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($ultimosSocios as $s)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50/70 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-xl bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-xs">
                                {{ strtoupper(substr($s->nombres, 0, 1)) }}
                            </div>
                            <div>
                                <span class="font-bold text-xs text-slate-900 dark:text-white block">{{ $s->paterno }} {{ $s->materno }} {{ $s->nombres }}</span>
                                <span class="text-[11px] text-slate-500">{{ $s->grado }} • {{ $s->arma_especialidad }}</span>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-slate-100 text-slate-600 uppercase">
                            {{ $s->situacion_actual }}
                        </span>
                    </div>
                @empty
                    <div class="p-6 text-center text-xs text-slate-400">Sin socios registrados recientemente.</div>
                @endforelse
            </div>
        </div>

        <!-- Últimos Pagos de Cajas -->
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 dark:text-white text-sm uppercase tracking-wider">Últimos Cobros en Ventanilla</h3>
                <a href="{{ route('cajas.historial') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">Ver arqueo →</a>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($ultimosPagos as $p)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50/70 transition-colors">
                        <div>
                            <span class="font-bold text-xs text-slate-900 dark:text-white block">{{ $p->inscripcion?->persona?->nombre_completo ?? 'N/A' }}</span>
                            <span class="text-[11px] text-slate-500">{{ $p->inscripcion?->horarioCurso?->disciplina?->nombre ?? 'Curso' }} • Recibo: {{ $p->numero_factura }}</span>
                        </div>
                        <span class="font-black text-xs text-blue-700 dark:text-blue-400">
                            Bs. {{ number_format($p->importe_final, 2) }}
                        </span>
                    </div>
                @empty
                    <div class="p-6 text-center text-xs text-slate-400">No hay cobros registrados hoy.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
