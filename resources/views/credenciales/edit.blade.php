@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Editar Credencial</h4>
        </div>
        <div class="card-body">
            <p class="mb-4">
                <strong>Para:</strong> {{ $model->paterno }} {{ $model->materno }}, {{ $model->nombres }}
                <br><strong>Número:</strong> {{ $credencial->codigo }}
            </p>

            <form action="{{ route('credenciales.update', ['tipo' => $tipo, 'id' => $id, 'credencial' => $credencial->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Estado Credencial <span class="text-danger">*</span></label>
                        <select name="estado" id="estado_credencial" class="form-select" required>
                            <option value="activo" {{ $credencial->estado == 'activo' ? 'selected' : '' }}>ACTIVO</option>
                            <option value="inactivo" {{ $credencial->estado == 'inactivo' ? 'selected' : '' }}>INACTIVO</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" id="label_motivo">Motivo <span class="text-danger">*</span></label>
                        <select name="estado_credencial" id="motivo_select" class="form-select" required>
                            <option value="{{ $credencial->estado_credencial }}">{{ $credencial->getMotivoAttribute() }}</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-warning btn-lg">
                        Actualizar Credencial
                    </button>
                    <a href="javascript:history.back()" class="btn btn-secondary btn-lg ms-3">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

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
        motivoSelect.innerHTML = '';

        if (motivos[estado]) {
            motivos[estado].forEach(m => {
                const option = document.createElement('option');
                option.value = m;
                option.textContent = m.replace(/_/g, ' ');
                if (m === "{{ $credencial->estado_credencial }}") option.selected = true;
                motivoSelect.appendChild(option);
            });
            labelMotivo.textContent = estado === 'activo' ? 'Motivo de Activación *' : 'Motivo de Inactivación *';
        }
    }

    actualizarMotivos();
    estadoSelect.addEventListener('change', actualizarMotivos);
});
</script>
@endsection