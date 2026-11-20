@extends('layouts.app')

@section('content')
<div class="container">

    <h3>Agregar Beneficiario al Socio Titular</h3>

    {{-- Datos del titular --}}
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <div class="row g-3">

            <!-- FILA 1: ID Titular + Código COE -->
            <div class="col-md-6">
                <p class="mb-2">
                    <strong class="text-muted">ID Titular:</strong> 
                    <span class="fs-5 text-primary fw-bold">{{ $socio->id_titular }}</span>
                </p>
            </div>
            <div class="col-md-6">
                <p class="mb-2">
                    <strong class="text-muted">Código COE:</strong> 
                    <span class="fs-5 text-dark fw-bold">{{ $socio->boleta_pago ?? 'No asignado' }}</span>
                </p>
            </div>

            <!-- FILA 2: Grado + Nombre completo -->
            <div class="col-md-6">
                <p class="mb-2">
                    <strong class="text-muted">Grado:</strong> 
                    <span class="badge bg-primary fs-6 px-3 py-1">{{ $socio->grado }}</span>
                    @if($socio->arma_especialidad)
                        <span class="text-secondary ms-2">({{ $socio->arma_especialidad }})</span>
                    @endif
                </p>
            </div>
            <div class="col-md-6">
                <p class="mb-2">
                    <strong class="text-muted">Nombre:</strong> 
                    <span class="fs-5 text-dark fw-bold">
                        {{ $socio->paterno }} {{ $socio->materno }}, {{ $socio->nombres }}
                    </span>
                </p>
            </div>

        </div>
    </div>
</div>

  <form action="{{ route('admin.socio.beneficiario.store', $socio->id_titular) }}" 
      method="POST" 
      enctype="multipart/form-data">
        @csrf

        {{-- Campo oculto para asociar --}}
        <input type="hidden" name="socio_titular_id" value="{{ $socio->id_titular }}">

        @include('beneficiario.form')

        <button type="submit" class="btn btn-success mt-3">Guardar Beneficiario</button>
        <a href="{{ route('admin.socio.beneficiario.index', $socio->id_titular) }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
</div>
@endsection
