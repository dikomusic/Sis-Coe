@extends('templates.erp-layout', ['title' => 'Subir Documento de Respaldo'])

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="border-b border-slate-200 dark:border-slate-800 pb-4">
        <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Cargar Documento de Respaldo</h2>
        <p class="text-xs text-slate-500 mt-0.5">Asociado a: <strong>{{ $persona->paterno ?? '' }} {{ $persona->nombres ?? '' }}</strong> ({{ strtoupper($tipo) }})</p>
    </div>

    <form method="POST" action="{{ route('documentos.store', ['tipo' => $tipo, 'id' => $id]) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Tipo de Documento *</label>
                <select name="tipo_documento" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3 focus:border-blue-600" required>
                    <option value="">-- Seleccionar Tipo --</option>
                    <option value="CEDULA_IDENTIDAD">Cédula de Identidad</option>
                    <option value="CERTIFICADO_MATRIMONIO">Certificado de Matrimonio</option>
                    <option value="CERTIFICADO_NACIMIENTO">Certificado de Nacimiento</option>
                    <option value="BOLETA_PAGO">Boleta de Pago</option>
                    <option value="ORDEN_DESTINO">Orden de Destino</option>
                    <option value="OTRO">Otro Documento</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Archivo (PDF o Imagen) *</label>
                <input type="file" name="archivo" accept=".pdf,image/*" required class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Descripción u Observación</label>
                <textarea name="descripcion" rows="3" placeholder="Detalle adicional..." class="block w-full rounded-xl border-slate-300 sm:text-xs py-2 px-3"></textarea>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/25 transition-all">
                Subir Documento
            </button>
        </div>
    </form>
</div>
@endsection
