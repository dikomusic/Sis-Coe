@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-4">
        <i class="bi bi-search"></i> Módulo de Consultas
    </h4>

{{-- BUSCADOR POR CREDENCIAL --}}
<form id="credencialForm" 
      action="{{ route('consultas.credencial.buscar') }}"
      method="GET"
      class="mb-4">

    <div class="input-group input-group-lg w-50">
        <input type="text"
               name="codigo"
               class="form-control"
               placeholder="Ingrese código o ID de credencial"
               required>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-search"></i> Buscar
        </button>
    </div>
</form>


    <div class="row g-3">
        <div class="col-md-6">
            <a href="{{ route('consultas.search') }}"
               class="card shadow-sm text-decoration-none">
                <div class="card-body">
                    <h5>Consulta por Socio Titular/Beneficiario</h5>
                    <p class="text-muted">
                        Ver estado, familiares, documentos y credenciales.
                    </p>
                </div>
            </a>
        </div>

        <div class="col-md-6">
            <a href="{{ route('consultas.beneficiario.buscar') }}"
               class="card shadow-sm text-decoration-none">
                <div class="card-body">
                    <h5>Consulta por Agregados/invitados</h5>
                    <p class="text-muted">
                        Información completa del beneficiario y su titular.
                    </p>
                </div>
            </a>
        </div>
    </div>

</div>

<script>
document.getElementById('credencialForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Evita el envío normal del formulario

    const form = this;
    const codigo = form.querySelector('input[name="codigo"]').value.trim();

    if (!codigo) {
        alert('Por favor ingrese un código o ID válido');
        return;
    }

    // Abre ventana emergente con tamaño controlado
    const width = 900;
    const height = 700;
    const left = (window.innerWidth - width) / 2;
    const top = (window.innerHeight - height) / 2;

    const popup = window.open(
        form.action + '?codigo=' + encodeURIComponent(codigo),
        'consultaCredencial',
        `width=${width},height=${height},left=${left},top=${top},scrollbars=yes,resizable=yes`
    );

    if (!popup) {
        alert('El navegador bloqueó la ventana emergente. Por favor permita popups para este sitio.');
    }
});
</script>

@endsection
