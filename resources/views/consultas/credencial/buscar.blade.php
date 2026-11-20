@extends('layouts.app')

@section('content')
<div class="container">

<div class="card shadow p-4">

<div class="row">

<div class="col-md-3 text-center">
    <img src="{{ asset('storage/'.$entidad->foto) }}"
         class="img-fluid rounded">
</div>

<div class="col-md-9">
    <h4>{{ $entidad->nombres }} {{ $entidad->paterno }} {{ $entidad->materno }}</h4>

    <p><strong>Tipo:</strong>
        {{ class_basename($credencial->entidad_type) }}
    </p>

    <p><strong>CI2:</strong> {{ $entidad->ci }}</p>
    <p><strong>Estado credencial:</strong>
        <span class="badge bg-success">{{ $credencial->estado }}</span>
    </p>

    <p><strong>Vence:</strong> {{ $credencial->fecha_vencimiento }}</p>
</div>

</div>
</div>

</div>
@endsection
