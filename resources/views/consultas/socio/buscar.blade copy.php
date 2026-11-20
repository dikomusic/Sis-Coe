

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Socios Titulares</h1>
        <a href="{{ route('socio.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> wwNuevo Socio
        </a>
    </div>

<!-- ====== BARRA DE BÚSQUEDA Y FILTROS ====== -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('socio.index') }}" class="row g-3 align-items-end">
            
            <!-- Búsqueda general (por apellidos, nombres, CI, boleta, etc.) -->
            <div class="col-md-4">
                <label class="form-label fw-semibold text-muted">Buscar socio</label>
                <input type="text" name="buscar" class="form-control" 
                       value="{{ request('buscar') }}" 
                       placeholder="Paterno, Nombres, CI, Boleta...">
            </div>

            <!-- Filtro por Grado -->
            <div class="col-md-2">
                <label class="form-label fw-semibold text-muted">Grado</label>
                <select name="grado" class="form-select">
                    <option value="">Todos</option>
                    <option value="SBTTE." {{ request('grado') == 'SBTTE.' ? 'selected' : '' }}>SBTTE.</option>
                    <option value="TTE." {{ request('grado') == 'TTE.' ? 'selected' : '' }}>TTE.</option>
                    <option value="CAP." {{ request('grado') == 'CAP.' ? 'selected' : '' }}>CAP.</option>
                    <option value="MY." {{ request('grado') == 'MY.' ? 'selected' : '' }}>MY.</option>
                    <option value="TCNL." {{ request('grado') == 'TCNL.' ? 'selected' : '' }}>TCNL.</option>
                    <option value="CNL." {{ request('grado') == 'CNL.' ? 'selected' : '' }}>CNL.</option>
                    <option value="GRAL." {{ request('grado') == 'GRAL.' ? 'selected' : '' }}>GRAL.</option>
                </select>
            </div>

            <!-- Filtro por Situación Actual -->
            <div class="col-md-2">
                <label class="form-label fw-semibold text-muted">Situación</label>
                <select name="situacion" class="form-select">
                    <option value="">Todos</option>
                    <option value="ACTIVO" {{ request('situacion') == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                    <option value="RESERVA ACTIVA" {{ request('situacion') == 'RESERVA ACTIVA' ? 'selected' : '' }}>RESERVA ACTIVA</option>
                    <option value="PASIVO" {{ request('situacion') == 'PASIVO' ? 'selected' : '' }}>PASIVO</option>
                    <option value="PASIVO(+)" {{ request('situacion') == 'PASIVO(+)' ? 'selected' : '' }}>PASIVO (+)</option>
                </select>
            </div>

            <!-- Filtro por Estado Socio -->
            <div class="col-md-2">
                <label class="form-label fw-semibold text-muted">Estado</label>
                <select name="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="Activo" {{ request('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
                    <option value="Inactivo" {{ request('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <!-- Botones -->
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    Buscar
                </button>
            </div>
        </form>

        <!-- Botón para limpiar filtros -->
        @if(request()->hasAny(['buscar', 'grado', 'situacion', 'estado']))
            <div class="mt-3">
                <a href="{{ route('socio.index') }}" class="btn btn-outline-secondary btn-sm">
                    Limpiar filtros
                </a>
            </div>
        @endif
    </div>
</div>
<!-- ====== FIN BARRA DE BÚSQUEDA ====== -->

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Código COE</th>
                            <th>Grado</th>
                            <th>Arma/Esp.</th>
                            <th>Paterno</th>
                            <th>Materno</th>
                            <th>Nombres</th>
                            <th>CI</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($socios as $s)
                        <tr>
                            <td>{{ $s->id_titular }}</td>
                            <td>{{ $s->boleta_pago }}</td>
                            <td><strong>{{ $s->grado }}</strong></td>
                            <td>{{ $s->arma_especialidad }}</td>
                            <td>{{ $s->paterno }}</td>
                            <td>{{ $s->materno }}</td>
                            <td>{{ $s->nombres }}</td>
                            <td>{{ $s->ci }}</td>
                            <td class="text-center">
                     <td class="text-center">
                        <div class="d-flex gap-2 justify-content-center">
                         <a href="{{ route('socio.edit', $s->id_titular) }}"
                            class="btn btn-outline-warning btn-sm">
                             Editar
                         </a>

                     <form action="{{ route('socio.destroy', $s->id_titular) }}" method="POST" class="d-inline">
                      @csrf @method('DELETE')
                     <button type="submit" class="btn btn-outline-danger btn-sm"
                    onclick="return confirm('¿Eliminar?')">
                Eliminar
            </button>
        </form>
        
        <a href="{{ route('socio.beneficiario.index', $s->id_titular) }}"
            class="btn btn-outline-info btn-sm">
            Beneficiarios
        </a>
        <a href="{{ route('documentos.create', ['tipo' => 'socio', 'id' => $s->id_titular]) }}" 
            class="btn btn-info btn-sm">
            Documentos
        </a>
        
        <a href="{{ route('credenciales.create', ['tipo' => 'socio', 'id' => $s->id_titular]) }}"
        class="btn btn-success btn-sm" target="_blank">
        Gestionar Credencial
        </a>

        <a href="{{ route('credenciales.imprimir', ['tipo' => 'socio', 'id' => $s->id_titular]) }}"
        class="btn btn-success btn-sm" 
        target="_blank">
        Imprimir Credencial
        </a>
    </div>
</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                No hay socios registrados aún.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginación bonita (Bootstrap 5) -->
        <div class="card-footer bg-white">
           {{ $socios->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

