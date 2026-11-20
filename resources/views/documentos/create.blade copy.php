@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Subir Documento de Respaldo</h3>
    <p><strong>Para:</strong> {{ $model->nombres }} {{ $model->paterno }}</p>

    <form action="{{ route('documentos.store', ['tipo' => $tipo, 'id' => $id]) }}" 
          method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">
            <div class="col-md-5">
                <label>Tipo de Documento</label>
                <select name="tipo_documento" class="form-select" required>
                    <option value="">Seleccionar</option>
                    <option value="COSSMI">COSSMI</option>
                    <option value="CI_FRONTAL">CI Frontal</option>
                    <option value="CI_POSTERIOR">CI Posterior</option>
                    <option value="CERTIFICADO_NACIMIENTO">Certificado Nacimiento</option>
                    <option value="CERTIFICADO_MATRIMONIO">Certificado Matrimonio</option>
                    <option value="CERTIFICADO_DIVORCIO">Certificado Divorcio</option>
                    <option value="CERTIFICADO_DEFUNCION">Certificado Defunción</option>
                    <option value="OTRO">Otro</option>
                </select>
            </div>
            <div class="col-md-5">
                <label>Archivo (JPG, PNG, PDF)</label>
                <input type="file" name="archivo" class="form-control" required accept="image/*,application/pdf">
            </div>
            <div class="col-md-2">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-success w-100">Subir</button>
            </div>
        </div>

        <div class="mt-3">
            <label>Descripción (opcional)</label>
            <input type="text" name="descripcion" class="form-control" placeholder="Ej: COSSMI renovado 2025">
        </div>
    </form>

    <hr>

    <h5>Documentos actuales</h5>
    @forelse($model->documentos as $doc)
        <div class="border rounded p-3 mb-2 d-flex justify-content-between">
            <div>
                <strong>{{ str_replace('_', ' ', $doc->tipo_documento) }}</strong>
                @if($doc->descripcion) — {{ $doc->descripcion }} @endif
            </div>
            <a href="{{ asset('storage/' . $doc->ruta_archivo) }}" target="_blank" class="btn btn-sm btn-primary">
                Ver
            </a>
        </div>
    @empty
        <p class="text-muted">No hay documentos</p>
    @endforelse

    <a href="javascript:history.back()" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection