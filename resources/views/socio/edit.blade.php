@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Socio Titular</h1>

    <!-- ¡¡IMPORTANTE!! enctype para que funcione la carga de imagen al editar -->
    <form action="{{ route('admin.socio.update', $socio->id_titular) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('socio.form')

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Actualizar Socio</button>
            <a href="{{ route('admin.socio.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection