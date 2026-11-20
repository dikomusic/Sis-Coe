@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Subir Documento de Respaldo</h4>
        </div>
        <div class="card-body">
            <p class="mb-3">
                <strong>Para:</strong> 
                <span class="text-primary fw-bold">
                    {{ $model->paterno }} {{ $model->materno }}, {{ $model->nombres }}
                </span>
                (ID: {{ $model->id ?? $model->id_titular }})
            </p>

            <!-- FORMULARIO PARA SUBIR NUEVO DOCUMENTO -->
            <form action="{{ route('documentos.store', ['tipo' => $tipo, 'id' => $id]) }}" 
                  method="POST" enctype="multipart/form-data" class="mb-5">
                @csrf

                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tipo de Documento <span class="text-danger">*</span></label>
                        <select name="tipo_documento" class="form-select" required>
                            <option value="">-- Seleccionar --</option>
                            <option value="COSSMI">COSSMI</option>
                            <option value="CI_FRONTAL">CI Frontal</option>
                            <option value="CI_POSTERIOR">CI Posterior</option>
                            <option value="CERTIFICADO_NACIMIENTO">Certificado de Nacimiento</option>
                            <option value="CERTIFICADO_MATRIMONIO">Certificado de Matrimonio</option>
                            <option value="CERTIFICADO_DIVORCIO">Certificado de Divorcio</option>
                            <option value="CERTIFICADO_DEFUNCION">Certificado de Defunción</option>
                            <option value="BOLETA_PAGO">Boleta de Pago</option>
                            <option value="RESOLUCION_BAJA">Resolución de Baja</option>
                            <option value="OTRO">Otro</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Archivo (JPG, PNG, PDF) <span class="text-danger">*</span></label>
                        <input type="file" name="archivo" class="form-control" required accept="image/*,application/pdf">
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100">
                            Subir Documento
                        </button>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label fw-semibold">Descripción (opcional)</label>
                    <input type="text" name="descripcion" class="form-control" 
                           placeholder="Ej: COSSMI renovado 2025">
                </div>
            </form>

            <hr class="my-5">

            <!-- DOCUMENTOS ACTUALES CON VISTA PREVIA -->
            <h5 class="mb-4 text-primary">Documentos Actuales</h5>

            @forelse($model->documentos as $doc)
                <div class="border rounded p-3 mb-3 shadow-sm bg-light">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <strong class="text-primary">
                                {{ str_replace('_', ' ', $doc->tipo_documento) }}
                            </strong>
                            @if($doc->descripcion)
                                <br><small class="text-muted">{{ $doc->descripcion }}</small>
                            @endif
                            <br><small class="text-muted">{{ $doc->created_at->format('d/m/Y H:i') }}</small>
                        </div>

                        <div class="col-md-6 text-center">
                            @php
                                $extension = pathinfo($doc->ruta_archivo, PATHINFO_EXTENSION);
                            @endphp

                            @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                <!-- VISTA PREVIA DE IMAGEN -->
                                <img src="{{ asset('storage/' . $doc->ruta_archivo) }}" 
                                     class="img-fluid rounded shadow" 
                                     style="max-height: 200px; object-fit: cover;"
                                     alt="{{ $doc->tipo_documento }}">
                            @else
                                <!-- ICONO PARA PDF U OTROS -->
                                <div class="bg-danger text-white rounded d-flex align-items-center justify-content-center" 
                                     style="height: 150px; font-size: 3rem;">
                                    PDF
                                </div>
                            @endif
                        </div>

                        <div class="col-md-3 text-end">
                            <a href="{{ route('documentos.edit', $doc) }}" class="btn btn-warning btn-sm me-2">
                                Editar
                            </a>
                            <a href="{{ asset('storage/' . $doc->ruta_archivo) }}" target="_blank" class="btn btn-primary btn-sm">
                                Ver / Descargar
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <p class="lead">No hay documentos de respaldo registrados</p>
                </div>
            @endforelse

            <div class="mt-4">
                <a href="javascript:history.back()" class="btn btn-secondary">
                    ← Volver
                </a>
            </div>
        </div>
    </div>
</div>
@endsection