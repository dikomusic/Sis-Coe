@extends('templates.erp-layout', ['title' => 'Editar Estado Credencial'])

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="border-b border-slate-200 dark:border-slate-800 pb-4">
        <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Editar Estado Credencial #{{ $credencial->id }}</h2>
        <p class="text-xs text-slate-500 mt-0.5">{{ $model->paterno }} {{ $model->materno }}, {{ $model->nombres }}</p>
    </div>

    <form action="{{ route('credenciales.update', ['tipo' => $tipo, 'id' => $id, 'credencial' => $credencial->id]) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Estado de la Credencial *</label>
                <select name="estado" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3 focus:border-blue-600" required>
                    <option value="activo" @selected($credencial->estado == 'activo')>ACTIVO</option>
                    <option value="inactivo" @selected($credencial->estado == 'inactivo')>INACTIVO</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Motivo / Condición *</label>
                <select name="estado_credencial" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3 focus:border-blue-600" required>
                    @foreach(['PRIMERA VEZ', 'RENOVACION', 'EXTRAVIO', 'DETERIORO', 'BAJA'] as $cond)
                        <option value="{{ $cond }}" @selected($credencial->estado_credencial == $cond)>{{ $cond }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('credenciales.create', ['tipo' => $tipo, 'id' => $id]) }}" class="px-5 py-2.5 rounded-xl text-xs font-bold border border-slate-300 hover:bg-slate-100 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/25 transition-all">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection
