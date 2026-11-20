@extends('layouts.credencial')

@section('title', 'Detalle Credencial - ' . ($entidad->nombres ?? 'No encontrado'))

@section('content')
<div class="container py-5 d-flex justify-content-center">
    <div class="card shadow-lg text-white" style="max-width: 700px; background: #0d1b2a; border: 2px solid #e30052; border-radius: 16px; overflow: hidden;">
        <!-- Título superior -->
        <div class="p-4 text-center" style="background: linear-gradient(135deg, #1b263b, #415a77);">
            <h5 class="mb-1 fw-bold text-uppercase">
                CÍRCULO DE OFICIALES DEL EJÉRCITO3
            </h5>

            <h3 class="mb-1 fw-bold text-uppercase" style="color: #e30052;">
                {{ class_basename($entidad) === 'SocioTitular' ? 'SOCIO TITULAR' :  ( $entidad->relacion ?? 'BENEFICIARIO') }}
            </h3>

            <h4 class="mb-0 fw-bold">
                {{ $entidad->grado?? '' }}
                {{ $entidad->arma_especialidad ?? '' }}    
                {{ $entidad->nombres ?? 'Nombre no disponible' }} 
                {{ $entidad->paterno ?? '' }} 
                {{ $entidad->materno ?? '' }}
            </h4>
        </div>

        <div class="card-body p-5">
            <!-- Mensaje si la credencial NO existe o está inactiva -->
            @if(!$credencial)
                <div class="alert text-center mb-5 py-4" 
                     style="background: rgba(227, 0, 82, 0.15); border: 2px solid #e30052; color: #e30052; border-radius: 12px;">
                    <i class="bi bi-x-circle-fill me-3 fs-3"></i>
                    <strong style="font-size: 1.4rem;">CREDENCIAL INEXISTENTE</strong>
                    <br>
                    <span class="d-block mt-2 fw-bold">
                        No se encontró ninguna credencial con el código ingresadoqq.
                    </span>
                </div>
            @elseif($credencial->estado !== 'activo')
                <div class="alert text-center mb-5 py-4" 
                     style="background: rgba(227, 0, 82, 0.15); border: 2px solid #e30052; color: #e30052; border-radius: 12px;">
                    <i class="bi bi-exclamation-triangle-fill me-3 fs-3"></i>
                    <strong style="font-size: 1.4rem;">CREDENCIAL INACTIVA</strong>
                    <br>
                    <span class="d-block mt-2 fw-bold">
                        Motivo: {{ $credencial->estado_credencial ?? 'Motivo no especificado' }}
                    </span>
                </div>
            @endif

            <!-- Datos principales (solo si existe entidad) -->
            @if($entidad)
                <div class="row g-5 align-items-start">
                    <!-- Foto -->
                    <div class="col-md-5 text-center">
                        @if($entidad->foto)
                            <img src="{{ asset('storage/' . $entidad->foto) }}" 
                                 alt="Foto"
                                 class="img-fluid rounded-circle shadow-lg mb-4"
                                 style="width: 200px; height: 200px; object-fit: cover; border: 5px solid #e30052;">
                        @else
                            <img src="{{ asset('images/default-avatar.jpg') }}" 
                                 alt="Sin foto"
                                 class="img-fluid rounded-circle shadow-lg mb-4"
                                 style="width: 200px; height: 200px; object-fit: cover; border: 5px solid #e30052;">
                        @endif
                    </div>

                    <!-- Información -->
                    <div class="col-md-7">
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <strong>CI1:</strong> {{ $entidad->ci ?? 'No registrado' }}
                            </div>

                            @if(class_basename($entidad) === 'SocioTitular')
                                <div class="col-12">
                                    <strong>Grado:</strong> {{ $entidad->grado ?? 'No registrado' }}
                                </div>
                                <div class="col-12">
                                    <strong>Boleta de Pago:</strong> {{ $entidad->boleta_pago ?? 'No registrado' }}
                                </div>
                            @else
                                <div class="col-12">
                                    <strong>Parentesco:</strong> {{ $entidad->parentesco ?? 'No especificada' }}
                                </div>
                                <div class="col-12">
                                    <strong>Socio Titular:</strong> 
                                    {{ $entidad->titular->nombres ?? 'No asociado' }} 
                                    {{ $entidad->titular->paterno ?? '' }}
                                    {{ $entidad->titular->materno ?? '' }}
                                </div>
                            @endif

                            <div class="col-12">
                                <strong>Tipo de Sangre:</strong> {{ $entidad->tipo_sangre ?? 'No registrado' }}
                            </div>

                            <div class="col-12">
                                <strong>Registro COE:</strong> {{ $entidad->registro_coe ?? 'No registrado' }}
                            </div>
                        </div>

                                <!-- Separador verde -->
                                <div class="progress mb-4" style="height: 8px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>

                                </div>
                                <!-- Estado y fechas (siempre seguro, sin error fatal) -->
                                <div class="row g-3 text-center">
                                    <div class="col-12 mb-3">
                                        <strong>ID Credencial:</strong><br>
                                        <span class="badge bg-{{ optional($credencial)->estado == 'activo' ? 'success' : 'danger' }} fs-5 px-5 py-2 mt-2 d-inline-block">
                                           {{ optional($credencial)->id ??  'No definida' }}
                                        </span>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <strong>Estado credencial:</strong><br>
                                        <span class="badge bg-{{ optional($credencial)->estado == 'activo' ? 'success' : 'danger' }} fs-5 px-5 py-2 mt-2 d-inline-block">
                                            {{ strtoupper(optional($credencial)->estado ?? 'INACTIVO') }}
                                        </span>
                                    </div>

                                    <div class="col-6">
                                        <strong>Fecha Emisión:</strong><br>
                                        {{ optional($credencial)->fecha_emision ? optional($credencial)->fecha_emision->format('d/m/Y') : 'No definida' }}
                                    </div>
                                    <div class="col-6">
                                        <strong>Fecha Vencimiento:</strong><br>
                                        {{ optional($credencial)->fecha_vencimiento ? optional($credencial)->fecha_vencimiento->format('d/m/Y') : 'No definida' }}
                                    </div>
                                </div>
                    </div>
                </div>

                <!-- Cuadro de datos del titular (solo si es beneficiario) -->
                @if(class_basename($entidad) !== 'SocioTitular' && $entidad->titular)
                    <div class="mt-5 p-4 rounded border border-secondary bg-dark-subtle text-white">
                        <h5 class="text-center mb-4 fw-bold">DATOS DEL SOCIO TITULAR</h5>
                        <div class="row g-3 text-center">
                            <div class="col-4">
                                <strong>GRADO:</strong><br>
                                {{ $entidad->titular->grado ?? 'No registrado' }}
                            </div>
                            <div class="col-4">
                                <strong>NOMBRE COMPLETO:</strong><br>
                                {{ $entidad->titular->nombres ?? 'No asociado' }} 
                                {{ $entidad->titular->paterno ?? '' }} 
                                {{ $entidad->titular->materno ?? '' }}
                            </div>
                            <div class="col-4">
                                <strong>CI:</strong><br>
                                {{ $entidad->titular->ci ?? 'No registrado' }}
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Botón Cerrar Ventana - centrado y separado -->
    <div class="text-center mt-5 pt-5 pb-5">
        <button onclick="window.close()" class="btn btn-outline-light btn-lg px-5 py-3 shadow">
            <i class="bi bi-x-circle me-2"></i> Cerrar Ventana
        </button>
    </div>
</div>
@endsection