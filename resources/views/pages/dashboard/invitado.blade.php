@extends('templates.erp-layout', ['title' => 'Portal de Consultas'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="border-b border-slate-200 dark:border-slate-800 pb-4">
        <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Portal de Operaciones e Invitado</h2>
        <p class="text-xs text-slate-500 mt-0.5">Control de acceso y consultas rápidas.</p>
    </div>

    <div class="p-6 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
        <h3 class="font-bold text-base text-slate-800 dark:text-white mb-2">Acceso a Consultas Generales</h3>
        <p class="text-xs text-slate-500 mb-4">Utilice el motor de búsqueda para validar carnets y socios en portería.</p>
        <a href="{{ route('consultas.index') }}" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition-all">
            Ir a Verificador de Credenciales →
        </a>
    </div>
</div>
@endsection
