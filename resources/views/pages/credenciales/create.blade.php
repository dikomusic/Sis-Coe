@extends('templates.erp-layout', ['title' => 'Emitir Credencial'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400">Carnetización Militar</span>
            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Emisión de Credencial</h2>
            <p class="text-xs text-slate-500">Para: <strong>{{ $model->paterno }} {{ $model->materno }}, {{ $model->nombres }}</strong> ({{ strtoupper($tipo) }})</p>
        </div>

        <div class="flex items-center gap-2">
            @if($credenciales->isNotEmpty())
                <a href="{{ route('credenciales.imprimir', ['tipo' => $tipo, 'id' => $id]) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-md shadow-purple-600/25 transition-all">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Imprimir Carnet Actual</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Formulario de Emisión -->
    <form action="{{ route('credenciales.store', ['tipo' => $tipo, 'id' => $id]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 border-b pb-2">Datos de la Credencial</h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Fecha de Emisión *</label>
                    <input type="date" name="fecha_emision" value="{{ now()->format('Y-m-d') }}" required class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Estado *</label>
                    <select name="estado" id="estado_credencial" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3" required onchange="actualizarMotivos(this.value)">
                        <option value="">-- Seleccionar --</option>
                        <option value="activo" selected>ACTIVO</option>
                        <option value="inactivo">INACTIVO</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Motivo / Tipo Emisión *</label>
                    <select name="estado_credencial" id="motivo_select" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2.5 px-3" required>
                        <option value="PRIMERA VEZ">PRIMERA VEZ</option>
                        <option value="RENOVACION">RENOVACIÓN</option>
                        <option value="EXTRAVIO">EXTRAVÍO / DUPLICADO</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Foto Frontal del Carnet (Opcional)</label>
                    <input type="file" name="imagen_frontal" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Foto Trasera del Carnet (Opcional)</label>
                    <input type="file" name="imagen_trasera" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700">
                </div>
            </div>

            <div class="pt-3 flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-md shadow-purple-600/25 transition-all">
                    Generar Nueva Credencial
                </button>
            </div>
        </div>
    </form>

    <!-- Historial de Credenciales -->
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="font-bold text-slate-800 dark:text-white text-base">Historial de Credenciales Emitidas</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-slate-800/50 text-[11px] uppercase tracking-wider font-bold text-slate-500 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-6 py-3.5">ID / Emisión</th>
                        <th class="px-6 py-3.5">Vencimiento</th>
                        <th class="px-6 py-3.5">Motivo</th>
                        <th class="px-6 py-3.5">Estado</th>
                        <th class="px-6 py-3.5 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($credenciales as $cred)
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-slate-900 dark:text-white">#{{ $cred->id }}</span>
                                <span class="block text-xs text-slate-400">{{ \Carbon\Carbon::parse($cred->fecha_emision)->format('d/m/Y') }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-blue-600">
                                {{ \Carbon\Carbon::parse($cred->fecha_vencimiento)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-slate-700 dark:text-slate-300">
                                {{ $cred->estado_credencial }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 text-[11px] font-bold rounded-full uppercase {{ $cred->estado === 'activo' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $cred->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('credenciales.edit', ['tipo' => $tipo, 'id' => $id, 'credencial' => $cred->id]) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                                    Editar Estado
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-slate-400 text-xs">
                                No hay credenciales registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function actualizarMotivos(estado) {
        const select = document.getElementById('motivo_select');
        select.innerHTML = '';
        if (estado === 'activo') {
            select.innerHTML = '<option value="PRIMERA VEZ">PRIMERA VEZ</option><option value="RENOVACION">RENOVACIÓN</option><option value="DUPLICADO">DUPLICADO</option>';
        } else {
            select.innerHTML = '<option value="EXTRAVIO">EXTRAVÍO</option><option value="DETERIORO">DETERIORO</option><option value="BAJA">BAJA / ANULADA</option>';
        }
    }
</script>
@endsection
