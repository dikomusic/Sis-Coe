@extends('templates.erp-layout', ['title' => 'Registrar Socio Titular'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4">
        <div>
            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Nuevo Socio Titular</h2>
            <p class="text-xs text-slate-500 mt-0.5">Formulario oficial de afiliación y registro de militar socio.</p>
        </div>
        <a href="{{ route('admin.socio.index') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
            ← Volver al listado
        </a>
    </div>

    <form method="POST" action="{{ route('admin.socio.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 border-b pb-2">1. Identificación y Grado Militar</h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Grado *</label>
                    <select name="grado" class="block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 sm:text-xs py-2.5 px-3 focus:border-blue-600" required>
                        <option value="">-- Seleccionar Grado --</option>
                        @foreach(['SBTTE.', 'TTE.', 'CAP.', 'MY.', 'TCNL.', 'CNL.', 'GRAL.'] as $g)
                            <option value="{{ $g }}" @selected(old('grado') == $g)>{{ $g }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Arma / Especialidad *</label>
                    <select name="arma_especialidad" id="armaSelect" class="block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 sm:text-xs py-2.5 px-3 focus:border-blue-600" required onchange="document.getElementById('armaManual').style.display = this.value === 'OTRO' ? 'block' : 'none'">
                        <option value="">-- Seleccionar --</option>
                        @foreach(['INF.', 'ING.', 'CAB.', 'LOG.', 'ART.', 'COM.', 'DIM.', 'DEM.', 'DAEN.', 'DAENMG.', 'BRIG.', 'DIV.', 'EJTO.'] as $arma)
                            <option value="{{ $arma }}" @selected(old('arma_especialidad') == $arma)>{{ $arma }}</option>
                        @endforeach
                        <option value="OTRO">Otro (Especificar)</option>
                    </select>
                    <input type="text" name="arma_especialidad_manual" id="armaManual" placeholder="Escriba arma/especialidad" class="mt-2 block w-full rounded-xl border-slate-300 sm:text-xs py-2 px-3" style="display: none;" value="{{ old('arma_especialidad_manual') }}">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Boleta de Pago</label>
                    <input type="text" name="boleta_pago" value="{{ old('boleta_pago') }}" placeholder="Número de boleta" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3 focus:border-blue-600">
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

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Carnet de Identidad *</label>
                    <input type="text" name="ci" value="{{ old('ci') }}" required placeholder="Número CI" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3 focus:border-blue-600">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Expedido</label>
                    <select name="expedido" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3">
                        @foreach(['LP', 'CB', 'SC', 'OR', 'PT', 'CH', 'TJ', 'BN', 'PA'] as $exp)
                            <option value="{{ $exp }}" @selected(old('expedido') == $exp)>{{ $exp }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Código Cossmil</label>
                    <input type="text" name="codigo_cossmil" value="{{ old('codigo_cossmil') }}" placeholder="Cossmil" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3">
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 border-b pb-2">2. Situación Institucional y Contacto</h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Situación Militar</label>
                    <select name="situacion_actual" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3">
                        @foreach(['ACTIVO', 'RESERVA ACTIVA', 'PASIVO', 'PASIVO(+)'] as $sit)
                            <option value="{{ $sit }}" @selected(old('situacion_actual') == $sit)>{{ $sit }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Estado Socio</label>
                    <select name="estado_socio" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3">
                        <option value="Activo" @selected(old('estado_socio') == 'Activo')>Activo</option>
                        <option value="Inactivo" @selected(old('estado_socio') == 'Inactivo')>Inactivo</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Celular / Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono') }}" placeholder="Teléfono" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3">
                </div>
            </div>

            <div class="pt-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Fotografía del Socio</label>
                <input type="file" name="foto" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.socio.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold border border-slate-300 hover:bg-slate-100 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/25 transition-all">
                Guardar Socio Titular
            </button>
        </div>
    </form>
</div>
@endsection
