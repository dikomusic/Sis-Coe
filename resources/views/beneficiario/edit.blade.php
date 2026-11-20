@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Beneficiario</h1>

   <form action="{{ route('admin.socio.beneficiario.update', [$socio->id_titular, $beneficiario->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('beneficiario.form')

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Actualizar Beneficiario</button>
            <a href="{{ route('admin.socio.beneficiario.index', $socio->id_titular) }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
