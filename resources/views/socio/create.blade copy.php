@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Registrar Socio Titular</h1>

    <form action="{{ route('socio.store') }}" method="POST">
        @csrf

        @include('socio.form')

        <button class="btn btn-success">Guardar</button>
    </form>
</div>
@endsection
