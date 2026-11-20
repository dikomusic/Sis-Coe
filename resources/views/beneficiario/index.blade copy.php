@extends('layouts.app')

@section('content')
<div class="container">

    <!-- TITULAR ARRIBA -->
    <div class="card mb-4 shadow-lg border-0">
    <div class="card-body p-4">
        <div class="row align-items-start g-4">
            <!-- ==================== FOTO IZQUIERDA ==================== -->
            <div class="col-lg-4 text-center">
                <div class="position-relative d-inline-block">
                    @if($socio->foto)
                        <img src="{{ asset('storage/' . $socio->foto) }}"
                             class="img-fluid rounded shadow"
                             style="width: 220px; height: 280px; object-fit: cover; border: 6px solid #0d6efd;"
                             alt="Foto de {{ $socio->paterno }} {{ $socio->nombres }}">
                        <div class="position-absolute bottom-0 start-50 translate-middle-x bg-primary text-white px-3 py-1 rounded-pill"
                             style="font-size: 0.85rem; font-weight: bold;">
                            SOCIO TITULAR
                        </div>
                    @else
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center text-white shadow"
                             style="width: 220px; height: 280px; font-size: 1.8rem;">
                            SIN FOTO
                        </div>
                    @endif
                </div>
            </div>

            <!-- ==================== DATOS DERECHA ==================== -->
            <div class="col-lg-8">
                <h3 class="text-primary fw-bold mb-3">
                    Ficha del Socio Titular
                </h3>

                <div class="row g-3">
                    <div class="col-md-4">
                        <p class="mb-2"><strong class="text-muted">ID Titular:</strong> 
                            <span class="fs-5 text-dark">{{ $socio->id_titular }}</span>
                        </p>
                    </div>
                 
                    <div class="col-md-4">
                        <p class="mb-2"><strong class="text-muted">Código COE:</strong> 
                            <span class="fs-5 text-dark">{{ $socio->boleta_pago ?? 'No asignado' }}</span>
                        </p>
                    </div>

                    <div class="col-md-4">
                        <p class="mb-2"><strong class="text-muted">Codigo Cossmil:</strong> 
                            <span class="fs-5 text-dark">{{ $socio->codigo_cossmil }}</span>
                        </p>
                    </div>   

                    <div class="col-12">
                        <p class="mb-2"><strong class="text-muted">Grado:</strong> 
                            <span class="badge bg-primary fs-6 px-3 py-2">{{ $socio->grado }} {{ $socio->arma_especialidad }}</span>
                        </p>
                    </div>

                    <div class="col-12">
                        <h5 class="text-dark fw-bold">
                            {{ $socio->paterno }} {{ $socio->materno }}, {{ $socio->nombres }}
                        </h5>
                        <p class="text-muted mb-2">Nombre completo</p>
                    </div>

                    <div class="col-md-6">
                        <p class="mb-2"><strong class="text-muted">CI:</strong> 
                            <span class="fs-5">{{ $socio->ci }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><strong class="text-muted">Situación:</strong> 
                            <span class="badge bg-{{ 
                                $socio->situacion_actual == 'ACTIVO' ? 'success' : 
                                ($socio->situacion_actual == 'RESERVA ACTIVA' ? 'warning' : 'secondary')
                            }} fs-6 px-3 py-2">
                                {{ $socio->situacion_actual }}
                            </span>
                        </p>
                    </div>

                    
                </div>

                <!-- Botones -->
                <div class="mt-4 d-flex flex-wrap gap-2">
                    <a href="{{ route('socio.index') }}" class="btn btn-outline-secondary">
                        ← Volver al Listado
                    </a>
                    <a href="{{ route('socio.edit', $socio->id_titular) }}" class="btn btn-warning">
                        Editar Socio
                    </a>
                    <a href="{{ route('socio.beneficiario.create', $socio->id_titular) }}" 
                       class="btn btn-success ms-auto">
                        + Agregar Beneficiario
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- LISTA DE BENEFICIARIOS -->
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Listado de Beneficiarios</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Parentesco</th>
                            <th>Nombre</th>
                            <th>CI</th>
                            <th>Estado</th>
                            <th>Observacion</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        
                        @forelse ($beneficiarios as $benef)
                        <tr>
                            <td>{{ $benef->id }}</td>
                            <td>{{ $benef->parentesco }}</td>
                            <td>{{ $benef->paterno }} {{ $benef->materno }} {{ $benef->nombres }}</td>
                            <td>{{ $benef->ci }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $benef->estado_beneficiario }}
                                </span>
                            </td>

                            <td>{{ $benef->observacion }}</td>

                            <td class="text-center">

                                <!-- EDITAR -->
                                <a href="{{ route('socio.beneficiario.edit', [$socio->id_titular, $benef->id]) }}"
                                   class="btn btn-warning btn-sm">
                                    Editar
                                </a>

                                <!-- ELIMINAR -->
                                <form action="{{ route('socio.beneficiario.destroy', [$socio->id_titular, $benef->id]) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Eliminar beneficiario?')">
                                    @csrf
                                    @method('DELETE')
                                    
                                    <button class="btn btn-danger btn-sm">
                                        Eliminar
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                No hay beneficiarios registrados.
                            </td>
                        </tr>
                        @endforelse


                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
