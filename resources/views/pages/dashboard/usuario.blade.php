@extends('templates.erp-layout', ['title' => 'Portal del Socio'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="border-b border-slate-200 dark:border-slate-800 pb-4">
        <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Portal Institucional del Socio</h2>
        <p class="text-xs text-slate-500 mt-0.5">Bienvenido, {{ auth()->user()->name }}. Consulta de servicios, credenciales y beneficios.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <a href="{{ route('consultas.index') }}" class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:border-blue-500 transition-all">
            <h3 class="font-bold text-base text-slate-800 dark:text-white mb-1">Verificar Estado de Credenciales</h3>
            <p class="text-xs text-slate-500">Revise la vigencia de su carnet y el de sus beneficiarios.</p>
        </a>

        <a href="{{ route('cajas.horarios') }}" class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:border-blue-500 transition-all">
            <h3 class="font-bold text-base text-slate-800 dark:text-white mb-1">Cursos y Disciplinas Disponibles</h3>
            <p class="text-xs text-slate-500">Natación, ráquetbol y actividades deportivas del COE.</p>
        </a>
    </div>
</div>
@endsection
