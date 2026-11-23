@extends('templates.erp-layout', ['title' => 'Registrar Beneficiario'])

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4">
        <div>
            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Nuevo Beneficiario</h2>
            @if($titular)
                <p class="text-xs text-slate-500 mt-0.5">Asignado al socio titular: <strong>{{ $titular->grado }} {{ $titular->paterno }} {{ $titular->nombres }}</strong> (ID: #{{ $titular->id_titular }})</p>
            @endif
        </div>
        @if($titular)
            <a href="{{ route('admin.socio.beneficiario.index', $titular->id_titular) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
                ← Volver
            </a>
        @endif
    </div>

    <form method="POST" action="{{ $titular ? route('admin.socio.beneficiario.store', $titular->id_titular) : route('beneficiarios.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Parentesco *</label>
                    <select name="parentesco" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3 focus:border-blue-600" required>
                        <option value="">-- Seleccionar Parentesco --</option>
                        @foreach(['ESPOSA', 'ESPOSO', 'HIJO', 'HIJA', 'PADRE', 'MADRE', 'HERMANO', 'HERMANA'] as $par)
                            <option value="{{ $par }}" @selected(old('parentesco') == $par)>{{ $par }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Carnet de Identidad</label>
                    <input type="text" name="ci" value="{{ old('ci') }}" placeholder="Número CI" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3 focus:border-blue-600">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Apellido Paterno *</label>
                    <input type="text" name="paterno" value="{{ old('paterno') }}" required placeholder="Paterno" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3 uppercase focus:border-blue-600">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Apellido Materno</label>
                    <input type="text" name="materno" value="{{ old('materno') }}" placeholder="Materno" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3 uppercase focus:border-blue-600">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Nombres *</label>
                    <input type="text" name="nombres" value="{{ old('nombres') }}" required placeholder="Nombres" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3 uppercase focus:border-blue-600">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Fotografía</label>
                    <input type="file" name="foto" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/25 transition-all">
                Guardar Beneficiario
            </button>
        </div>
    </form>
</div>
@endsection
