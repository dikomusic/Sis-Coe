@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 600px">

    <h3 class="mb-4">
        Registrar {{ strtoupper($rol) }}
    </h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('usuarios.store') }}">
        @csrf

        <input type="hidden" name="rol" value="{{ $rol }}">

        <div class="mb-3">
            <label>Nombre completo</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Correo electrónico</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Confirmar contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button class="btn btn-primary w-100">
            Guardar {{ strtoupper($rol) }}
        </button>

    </form>

</div>
@endsection
