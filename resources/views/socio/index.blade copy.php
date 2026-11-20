@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Socios Titulares</h1>

    <a href="{{ route('socio.create') }}" class="btn btn-primary mb-3">Nuevo Socio</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Codigo COE</th>
                <th>Grado</th>
                <th>Arma_Especialidad</th>
                <th>Paterno</th>
                <th>Materno</th>
                <th>Nombres</th>
                <th>CI</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($socios as $s)
            <tr>
                <td>{{ $s->id_titular }}</td>
                <td>{{ $s->boleta_pago }}</td>
                <td>{{ $s->grado }}</td>
                <td>{{ $s->arma_especialidad }}</td>  
                <td>{{ $s->paterno }}</td>
                <td>{{ $s->materno }}</td>
                <td>{{ $s->nombres }}</td>
                <td>{{ $s->ci }}</td>
                <td>
                    <a href="{{ route('socio.edit', $s->id_titular) }}" class="btn btn-warning btn-sm">Editar</a>

                    <form action="{{ route('socio.destroy', $s->id_titular) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
