@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-4">
        <i class="bi bi-card-heading"></i>
        Resultado de Credencial
    </h4>

    <div class="card shadow">
        <div class="card-body row">

            {{-- FOTO --}}
            <div class="col-md-3 text-center">
                <img src="{{ asset('storage/'.$entidad->foto) }}"
                     class="img-fluid rounded border"
                     alt="Foto"
                     onerror="this.src='{{ asset('img/avatar.png') }}'">
            </div>

            {{-- DATOS --}}
            <div class="col-md-9">
                <h5 class="mb-2">
                    {{ $entidad->nombres }} {{ $entidad->paterno }} {{ $entidad->materno }}
                </h5>

                <p class="mb-1"><strong>CI11:</strong> {{ $entidad->ci }}</p>
                <p class="mb-1"><strong>Tipo:</strong>
                    {{ class_basename($entidad) }}
                </p>

                <hr>

                <p><strong>Credencial:</strong> {{ $credencial->codigo }}</p>
                <p><strong>Estado:</strong>
                    <span class="badge bg-success">
                        {{ strtoupper($credencial->estado) }}
                    </span>
                </p>
                <p><strong>Vigencia:</strong>
                    {{ $credencial->fecha_emision }} →
                    {{ $credencial->fecha_vencimiento }}
                </p>
            </div>

        </div>
    </div>

</div>
@endsection
