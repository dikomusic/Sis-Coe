@extends('layouts.credencial')

@section('title', 'Detalle Credencial - ' . ($entidad->nombres ?? 'No encontrado'))

@section('content')


<div class="container py-2 d-flex justify-content-center">
    <div class="w-100" style="max-width: 700px;">
        @if($credencial)
            
            @if($mensajeError ?? false)
                <div class="alert text-center mb-4" 
                    style="background: rgba(227, 0, 82, 0.15); border: 1px solid #e30052; color: #e30052;">
                    <strong style="color: #e30052; font-size: 1.3rem;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        ATENCIÓN: Credencial INACTIVA
                    </strong>
                    <br>
                    <span class="d-block mt-2 fw-bold">
                        Motivo: {{ $credencial->estado_credencial ?? 'Motivo no especificado' }}
                    </span>
                </div>
            @endif
        
            <!-- Tarjeta principal cuando SÍ existe la credencial -->
            <div class="card shadow-lg text-white" style="background: #0d1b2a; border: 1px solid #e30052; border-radius: 16px; overflow: hidden;">
                <!-- Título superior -->
                <div class="p-4 text-center" style="background: linear-gradient(135deg, #1b263b, #415a77);">
                    <h5 class="mb-1 fw-bold text-uppercase">
                        CÍRCULO DE OFICIALES DEL EJÉRCITO
                    </h5>

                    <h3 class="mb-1 fw-bold text-uppercase" style="color: #e30052;">
                        {{ class_basename($entidad) === 'SocioTitular' ? 'SOCIO TITULAR' : ($entidad->relacion ?? 'BENEFICIARIO') }}
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
                    <div class="row g-4 align-items-start">
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
                                <div class="col-12 mb-3">
                                    <strong>Estado credencial:</strong><br>
                                    <span class="badge bg-{{ ($credencial->estado ?? 'inactivo') == 'activo' ? 'success' : 'danger' }} fs-5 px-5 py-2 mt-2 d-inline-block">
                                        {{ strtoupper($credencial->estado ?? 'INACTIVO') }}
                                    </span>
                                </div>

                        </div>

                        <!-- Datos principales -->
                        <div class="col-md-7">
                            <div class="row g-3 mb-4">
                                @if(class_basename($entidad) === 'SocioTitular')
                                    <div class="col-12">
                                        <strong>Grado:</strong> {{ $entidad->grado ?? 'No registrado' }}
                                    </div>
                                @else
                                    <div class="col-12">
                                        <strong>Parentesco:</strong> {{ $entidad->parentesco ?? 'No especificada' }}
                                    </div>
                                @endif
                                
                                <div class="col-12">
                                    <strong>CI:</strong> {{ $entidad->codigo_cossmil ?? 'No registrado' }}
                                </div>

                                @if(class_basename($entidad) === 'SocioTitular')
                                    <div class="col-12">
                                        <strong>Registro COE:</strong> {{ $entidad->boleta_pago ?? 'No registrado' }}
                                    </div>
                                @else
                                    <div class="col-12">
                                        <strong>Registro COE:</strong> {{ $entidad->titular->boleta_pago ?? 'No registrado' }}
                                    </div>
                                @endif

                                <div class="col-12">
                                    <strong>Tipo de Sangre:</strong> {{ $entidad->tipo_sangre ?? 'No registrado' }}
                                </div>
                            </div>

                            <!-- Separador verde -->
                            <div class="progress mb-4" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                            </div>

                            <!-- Estado y fechas -->
                            <div class="row g-3 text-center">

                                <div class="col-6">
                                    <strong>Fecha Emisión:</strong><br>
                                    {{ $credencial->fecha_emision ? $credencial->fecha_emision->format('d/m/Y') : 'No definida' }}
                                </div>
                                <div class="col-6">
                                    <strong>Fecha Vencimiento:</strong><br>
                                    {{ $credencial->fecha_vencimiento ? $credencial->fecha_vencimiento->format('d/m/Y') : 'No definida' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cuadro de datos del titular (solo si es beneficiario) -->
                    @if(class_basename($entidad) !== 'SocioTitular' && $entidad->titular)
                        <div class="p-4 text-center" style="background: linear-gradient(135deg, #1b263b, #415a77);">
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
                </div>
            @else
                <!-- Mensaje cuando NO existe la credencial -->
                   <div class="text-center py-5">
                        <div class="alert alert-danger fs-4 shadow-lg" style="background: rgba(227, 0, 82, 0.15); border: 2px solid #e30052; color: #e30052;">
                        <i class="bi bi-x-circle-fill me-3 fs-3"></i>
                        <strong>CREDENCIAL INEXISTENTE</strong>
                        </div>
                    
                        <p class="text-muted mt-4 fs-5" style="color: white !important;">
                        No se encontró ninguna credencial activa o registrada con el código ingresado.
                        </p>
                    
                        <p class="text-muted mt-4 fs-5" style="color: white !important;">
                        Verifique el número de credencial e intente nuevamente.
                        </p>
                    </div>

            @endif
        </div>

        <div class="text-center mt-2 pt-2 pb-4">
            <button onclick="window.close()" class="btn btn-outline-light btn-lg px-5 py-3 shadow">
                <i class="bi bi-x-circle me-2"></i> Cerrar Ventana
            </button>
        </div>
    </div>
</div>
@endsection