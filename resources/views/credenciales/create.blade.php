@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">Generar Credencial</h4>
        </div>
        <div class="card-body">
            <p class="mb-4">
                <strong>Para:</strong> 
                <span class="text-success fw-bold fs-5">
                    {{ $model->paterno }} {{ $model->materno }}, {{ $model->nombres }}
                </span>
                (ID: {{ $model->id ?? $model->id_titular }})
            </p>

            <!-- FORMULARIO PARA GENERAR CREDENCIAL -->
<form action="{{ route('credenciales.store', ['tipo' => $tipo, 'id' => $id]) }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-3">
        <div class="col-md-4">
            <label>Fecha de Emisión *</label>
            <input type="date" name="fecha_emision" class="form-control" required value="{{ now()->format('Y-m-d') }}">
        </div>

        <div class="col-md-4">
            <label>Estado Credencial *</label>
            <select name="estado" id="estado_credencial" class="form-select" required>
                <option value="">-- Seleccionar --</option>
                <option value="activo">ACTIVO</option>
                <option value="inactivo">INACTIVO</option>
            </select>
        </div>

        <div class="col-md-4">
            <label id="label_motivo">Motivo *</label>
            <select name="estado_credencial" id="motivo_select" class="form-select" required>
                <option value="">-- Seleccionar motivo --</option>
            </select>
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-md-6">
            <label>Foto Frontal del Carné (opcional)</label>
            <input type="file" name="imagen_frontal" class="form-control" accept="image/*">
        </div>
        <div class="col-md-6">
            <label>Foto Trasera del Carné (opcional)</label>
            <input type="file" name="imagen_trasera" class="form-control" accept="image/*">
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-success btn-lg">
            Generar Credencial
        </button>
    </div>
</form>

            <hr class="my-5">

            <!-- LISTA DE CREDENCIALES ACTUALES -->
            <h5 class="text-success mb-4">Credenciales Registradas</h5>

            @forelse($model->credenciales as $cred)
                <div class="border rounded p-3 mb-3 bg-light shadow-sm">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <strong class="text-success fs-5">{{ $cred->numero_credencial }}</strong>

                            <span class="badge bg-warning text-dark fs-6 px-3 py-2 ms-2">
                                <small>ID: {{ str_replace('_', ' ', $cred->id) }}</small>
                            </span>

                            <small>Emisión: {{ \Carbon\Carbon::parse($cred->fecha_emision)->format('d/m/Y') }}</small>
                            <small>Vencimiento: {{ \Carbon\Carbon::parse($cred->fecha_vencimiento)->format('d/m/Y') }}</small>
                            @if($cred->observaciones)
                                <br><small class="text-muted">{{ $cred->observaciones }}</small>
                            @endif
                        </div>
                        
                        <div class="col-md-5">
                            <!-- ESTADO PRINCIPAL -->
                            <span class="badge {{ $cred->estado == 'activo' ? 'bg-success' : 'bg-danger' }} fs-5 px-4 py-2">
                                {{ $cred->estado == 'activo' ? 'ACTIVO' : 'INACTIVO' }}
                            </span>

                            <!-- MOTIVO AL LADO (estado_credencial) -->
                            <span class="badge bg-secondary fs-6 px-3 py-2 ms-2">
                                {{ str_replace('_', ' ', $cred->estado_credencial) }}
                            </span>

                            <!-- OPCIONAL: FECHA VENCIMIENTO SI ES INACTIVO POR CADUCIDAD -->
                            @if($cred->estado_credencial == 'BAJA_POR_CADUCIDAD')
                                <small class="text-muted d-block mt-1">
                                    Venció: {{ $cred->fecha_vencimiento->format('d/m/Y') }}
                                </small>
                            @endif
                        </div>

                        <div class="col-md-3 text-end">
                            <a href="{{ route('credenciales.edit', ['tipo' => $tipo, 'id' => $id, 'credencial' => $cred->id]) }}" 
                            class="btn btn-warning btn-sm me-2">
                                Editar Estado
                            </a>
                            <a href="{{ route('credenciales.imprimir', ['tipo' => $tipo, 'id' => $id]) }}" 
                            class="btn btn-primary btn-sm" target="_blank">
                                Imprimir
                            </a>
                        </div>


                    </div>
                </div>
            @empty
                <p class="text-muted text-center py-4">No hay credenciales registradas aún</p>
            @endforelse

            <div class="mt-4">
                <a href="javascript:history.back()" class="btn btn-secondary">
                    ← Volver
                </a>
            </div>
        </div>
    </div>
</div>


<!-- SCRIPT DINÁMICO -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const estadoSelect = document.getElementById('estado_credencial');
    const motivoSelect = document.getElementById('motivo_select');
    const labelMotivo = document.getElementById('label_motivo');

    const motivos = {
        activo: ['AFILIACION', 'RENOVACION_POR_PERDIDA', 'RENOVACION_POR_CADUCIDAD'],
        inactivo: ['BAJA_POR_PERDIDA', 'BAJA_POR_MAYORIA_EDAD', 'BAJA_POR_DIVORCIO', 'BAJA_POR_CADUCIDAD']
    };

    function actualizarMotivos() {
        const estado = estadoSelect.value;
        motivoSelect.innerHTML = '<option value="">-- Seleccionar motivo --</option>';

        if (motivos[estado]) {
            motivos[estado].forEach(m => {
                const option = document.createElement('option');
                option.value = m;
                option.textContent = m.replace(/_/g, ' ');
                motivoSelect.appendChild(option);
            });
            labelMotivo.textContent = estado === 'activo' ? 'Motivo de Activación *' : 'Motivo de Baja *';
        }
    }

    estadoSelect.addEventListener('change', actualizarMotivos);
    actualizarMotivos(); // Ejecutar al cargar si hay old
});
</script>

@endsection