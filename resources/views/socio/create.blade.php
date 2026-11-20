@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Registrar Socio Titular</h1>

    <form action="{{ route('admin.socio.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @include('socio.form')

        <div class="mt-4">
            <button type="submit" class="btn btn-success">Guardar Socio</button>
            <a href="{{ route('admin.socio.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection