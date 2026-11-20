@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Socio</h1>

    <form action="{{ route('socio.update', $socio->id_titular) }}" method="POST">
        @csrf
        @method('PUT')

        @include('socio.form')

        <button class="btn btn-primary">Actualizar</button>
    </form>
</div>
@endsection
