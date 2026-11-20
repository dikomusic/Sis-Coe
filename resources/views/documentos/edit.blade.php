@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-warning text-dark">
            <h4>Editar Documento de Respaldo</h4>
        </div>
        <div class="card-body">
            <p><strong>Para:</strong> {{ $model->paterno }} {{ $model->materno }}, {{ $model->nombres }}</p>

            <form action="{{ route('documentos.update', $documento) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-5">
                        <label>Tipo de Documento</label>
                        <select name="tipo_documento" class="form-select" required>
                            <option value="">-- Seleccionar --</option>
                            @foreach(['COSSMI','CI_FRONTAL','CI_POSTERIOR','CERTIFICADO_NACIMIENTO','CERTIFICADO_MATRIMONIO','CERTIFICADO_DIVORCIO','CERTIFICADO_DEFUNCION','BOLETA_PAGO','RESOLUCION_BAJA','OTRO'] as $tipo)
                                <option value="{{ $tipo }}" {{ $documento->tipo_documento == $tipo ? 'selected' : '' }}>
                                    {{ str_replace('_', ' ', $tipo) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label>Archivo actual</label>
                        <div class="border rounded p-3 bg-light text-center">
                            @if(in_array(pathinfo($documento->ruta_archivo, PATHINFO_EXTENSION), ['jpg','jpeg','png','gif','webp']))
                                <img src="{{ asset('storage/' . $documento->ruta_archivo) }}" class="img-fluid rounded" style="max-height: 200px;">
                            @else
                                <div class="bg-danger text-white rounded d-inline-block px-4 py-5">PDF</div>
                            @endif
                            <p class="mt-2 mb-0"><strong>{{ $documento->tipo_documento }}</strong></p>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <label>Reemplazar archivo (opcional)</label>
                    <input type="file" name="archivo" class="form-control" accept="image/*,application/pdf">
                </div>

                <div class="mt-3">
                    <label>Descripción</label>
                    <input type="text" name="descripcion" class="form-control" 
                           value="{{ old('descripcion', $documento->descripcion) }}" 
                           placeholder="Ej: COSSMI renovado 2025">
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Actualizar Documento</button>
                    <a href="javascript:history.back()" class="btn btn-secondary">Cancelar</a>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection