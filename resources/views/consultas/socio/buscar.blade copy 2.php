@extends('layouts.app')

@section('title', 'Módulo de Consultas')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-white">Módulo de Consultas</h2>
        <p class="text-muted">Seleccione el modo de búsqueda y escriba el filtro o ID</p>
    </div>

    <!-- Formulario principal -->
    <form id="consultaForm" action="{{ route('consultas.search') }}" method="GET" class="mb-5">
        <div class="input-group mb-4">
            <input type="text" name="q" class="form-control form-control-lg" 
                   placeholder="Buscar o filtrar..." value="{{ $query ?? '' }}" required>
            <button class="btn btn-primary btn-lg" type="submit">
                <i class="bi bi-search me-2"></i> Buscar
            </button>
        </div>

        <!-- Botones de radio + selección SOCIO / BENEFICIARIO / TODOS -->
        <div class="d-flex flex-wrap gap-4 justify-content-center align-items-center mb-4">
            <!-- Radio para modo -->
            <div class="btn-group" role="group">
                <input type="radio" name="mode" value="ID" id="modeID" class="btn-check" {{ ($mode ?? 'TODOS') === 'ID' ? 'checked' : '' }}>
                <label class="btn btn-outline-primary" for="modeID">ID</label>

                <input type="radio" name="mode" value="TODOS" id="modeTODOS" class="btn-check" {{ ($mode ?? 'TODOS') === 'TODOS' ? 'checked' : '' }}>
                <label class="btn btn-outline-primary" for="modeTODOS">TODOS</label>
            </div>

            <!-- Tipo de entidad (solo visible si mode = TODOS o ID) -->
            <div class="btn-group" role="group" id="tipoEntidadGroup" style="{{ ($mode ?? 'TODOS') === 'TODOS' || ($mode ?? 'TODOS') === 'ID' ? '' : 'display:none;' }}">
                <input type="radio" name="tipo" value="SOCIO" id="tipoSocio" class="btn-check" {{ ($tipo ?? 'SOCIO') === 'SOCIO' ? 'checked' : '' }}>
                <label class="btn btn-outline-success" for="tipoSocio">SOCIO</label>

                <input type="radio" name="tipo" value="BENEFICIARIO" id="tipoBenef" class="btn-check" {{ ($tipo ?? 'SOCIO') === 'BENEFICIARIO' ? 'checked' : '' }}>
                <label class="btn btn-outline-info" for="tipoBenef">BENEFICIARIO</label>
            </div>
        </div>
    </form>

    <!-- Resultados -->
    @if(isset($socios) || isset($beneficiarios))
        <!-- Tabla de Socios (solo si se buscó SOCIO o TODOS) -->
        @if(isset($socios) && $socios->count() > 0)
            <h5 class="mt-5 mb-3 text-white">Resultados para Socios</h5>
            <div class="table-responsive">
                <table class="table table-dark table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Código COE</th>
                            <th>Grado/Arma/Esp</th>
                            <th>Paterno</th>
                            <th>Materno</th>
                            <th>Nombres</th>
                            <th>CI</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($socios as $socio)
                            <tr>
                                <td>{{ $socio->codigo_coe }}</td>
                                <td>{{ $socio->grado_arma_esp }}</td>
                                <td>{{ $socio->paterno }}</td>
                                <td>{{ $socio->materno }}</td>
                                <td>{{ $socio->nombres }}</td>
                                <td>{{ $socio->ci }}</td>
                                <td>
                                    <a href="{{ route('consultas.detalle', ['id' => $socio->id_titular, 'tipo' => 'socio']) }}" 
                                       target="_blank" class="btn btn-sm btn-success">
                                        VER FICHA
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $socios->links() }}
        @endif

        <!-- Tabla de Beneficiarios (solo si se buscó BENEFICIARIO o TODOS) -->
        @if(isset($beneficiarios) && $beneficiarios->count() > 0)
            <h5 class="mt-5 mb-3 text-white">Resultados para Beneficiarios</h5>
            <div class="table-responsive">
                <table class="table table-dark table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Parentesco</th>
                            <th>Nombre</th>
                            <th>CI</th>
                            <th>Estado</th>
                            <th>Observación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($beneficiarios as $benef)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $benef->parentesco }}</td>
                                <td>{{ $benef->nombres }}</td>
                                <td>{{ $benef->ci }}</td>
                                <td>
                                    <span class="badge bg-{{ $benef->estado == 'ACTIVO' ? 'success' : 'danger' }}">
                                        {{ $benef->estado }}
                                    </span>
                                </td>
                                <td>{{ $benef->observacion ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('consultas.detalle', ['id' => $benef->id_beneficiario, 'tipo' => 'beneficiario']) }}" 
                                       target="_blank" class="btn btn-sm btn-success">
                                        VER FICHA
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $beneficiarios->links() }}
        @endif

        @if((isset($socios) && $socios->count() === 0) && (isset($beneficiarios) && $beneficiarios->count() === 0))
            <div class="alert alert-info text-center mt-5">
                No se encontraron resultados con el criterio ingresado.
            </div>
        @endif
    @endif
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('input[name="mode"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const group = document.getElementById('tipoEntidadGroup');
        if (this.value === 'ID') {
            group.style.display = 'inline-flex';
        } else {
            group.style.display = 'none';
        }
    });
});
</script>
@endpush