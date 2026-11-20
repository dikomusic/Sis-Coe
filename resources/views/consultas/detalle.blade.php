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
                    <strong style="font-size: 1.4rem;">ACTUALIZAR Y/O RENOVAR CREDENCIAL</strong>
                    <br>
                    <span class="d-block mt-2 fw-bold">
                        SOCIO/BENEFICIARIO REGISTRADO por favor comunicarse con carnetizaciones para actualizar credencial.
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
                                <div class="col-12 mb-3">
                                    <strong>Estado credencial:</strong><br>
                                    <span class="badge bg-{{ ($credencial->estado ?? 'inactivo') == 'activo' ? 'success' : 'danger' }} fs-5 px-1 py-2 mt-2 d-inline-block">
                                        <strong>ID : </strong>{{ optional($credencial)->id ??  'No definida' }}<br>
                                        {{ strtoupper($credencial->estado ?? 'INACTIVO') }}
                                    </span>
                                </div>

                        </div>

                    <!-- Información -->
                    <div class="col-md-7">
                        <div class="row g-3 mb-4">
                            @if(class_basename($entidad) === 'SocioTitular')
                                    
                                    <div class="col-12" style="font-size: 20px;">
                                        <strong>Registro COE:</strong> {{ $entidad->boleta_pago ?? 'No registrado' }}
                                    </div>

                                    <div class="col-12" style="font-size: 20px;">
                                        <strong>Matricula Cossmil:</strong> {{ $entidad->codigo_cossmil ?? 'No registrado' }}
                                    </div>

                                    <div class="col-12" style="font-size: 20px;">
                                    <strong>CI:</strong> {{ $entidad->ci ?? 'No registrado' }}
                                    </div>

                                    <div class="col-12" style="font-size: 20px;">
                                    <strong>Tipo de Sangre:</strong> {{ $entidad->tipo_sangre ?? 'No registrado' }}
                                    </div> 
                                    <div class="col-12" style="font-size: 20px;">
                                        <strong>Promoción:</strong> 
                                        {{ $entidad->fecha_alta_ejercito 
                                            ? \Carbon\Carbon::parse($entidad->fecha_alta_ejercito)->format('Y') 
                                            : 'No registrado' }}
                                    </div>
                                    <div class="col-12" style="font-size: 20px;">
                                    <strong>Situación Activo:</strong> {{ $entidad->situacion_actual ?? 'No registrado' }}
                                    </div>  
                               

                                @else
                                    <div class="col-12" style="font-size: 20px;">
                                        <strong>Registro COE:</strong> {{ $entidad->titular->boleta_pago ?? 'No registrado' }}
                                    </div>

                                    <div class="col-12" style="font-size: 20px;">
                                    <strong>Matricula Cossmil:</strong> {{ $entidad->codigo_cossmil ?? 'No registrado' }}
                                    </div>

                                    <div class="col-12" style="font-size: 20px;">
                                    <strong>CI:</strong> {{ $entidad->ci ?? 'No registrado' }}
                                    </div>

                                    <div class="col-12" style="font-size: 20px;">
                                    <strong>Tipo de Sangre:</strong> {{ $entidad->tipo_sangre ?? 'No registrado' }}
                                    </div>

                                    <div class="col-12" style="font-size: 20px;">
                                        <strong>Fecha de Nacimiento:</strong>

                                        {{ $entidad->fecha_nacimiento 
                                            ? \Carbon\Carbon::parse($entidad->fecha_nacimiento)->format('d-m-Y') 
                                            : 'No registrado' }}
                                    </div>

                                    <div class="col-12" style="font-size: 20px;">
                                        <strong>Parentesco:</strong> {{ $entidad->parentesco ?? 'No especificada' }}
                                    </div>
                                @endif
                        </div>

                                <!-- Separador verde -->
                                <div class="progress mb-4" style="height: 8px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>

                                </div>
                                
                                <!-- Estado de credencial TITULAR Y BENEFICIARIO) -->

                    </div>
                                            @if(class_basename($entidad) === 'SocioTitular')
                                            <div class="p-3 text-center" style="background: linear-gradient(135deg, #3b1b3b, #5f465b); line-height: 1.2;">
                        
                                                <h5 class="text-center mb-2 fw-bold" style="font-size: 18px; margin-bottom:8px;">
                                                    VIGENCIA CREDENCIAL
                                                </h5>

                                                <div class="row g-2 text-center">

                                                    <!-- FECHA EMISION -->
                                                    <div class="col-4" style="font-size: 18px; line-height:1.1;">
                                                        <strong>Fecha Emisión:</strong><br>
                                                        {{ $credencial?->fecha_emision?->format('d/m/Y') ?? 'En credencial antiguo' }}
                                                    </div>

                                                    <!-- FECHA VENCIMIENTO -->
                                                    <div class="col-4" style="font-size: 18px; line-height:1.1;">
                                                        <strong>Fecha Vencimiento:</strong><br>
                                                        @if($credencial?->fecha_vencimiento)

                                                            @if($credencial->fecha_vencimiento->format('Y-m-d') == '2099-12-31')
                                                                INDEFINIDO
                                                            @else
                                                                {{ $credencial->fecha_vencimiento->format('d-m-Y') }}
                                                            @endif

                                                        @else
                                                            En credencial antiguo
                                                        @endif
                                                    </div>

                                                    <!-- VIGENCIA TITULAR -->
                                                    <div class="col-4" style="font-size: 18px; line-height:1.1;">

                                                        <strong>Vig. Titular:</strong><br>

                                                        @if(class_basename($entidad) === 'BeneficiarioTitular')

                                                            @if(in_array($entidad->parentesco, ['HIJO', 'HIJA', 'HIJASTRO', 'HIJASTRA']))

                                                                {{ \Carbon\Carbon::parse($entidad->fecha_nacimiento)
                                                                    ->addYears(28)
                                                                    ->format('d-m-Y') }}

                                                            @elseif(in_array($entidad->parentesco, ['ESPOSA', 'ESPOSO']))

                                                                INDEFINIDO

                                                            @else

                                                                No definido

                                                            @endif

                                                        @else

                                                            No aplica

                                                        @endif

                                                    </div>

                                                </div>
                                            </div><br>
                        @else

                            <div class="p-3 text-center" style="background: linear-gradient(135deg, #3b1b3b, #5f465b); line-height: 1.2;">
        
                                <h5 class="text-center mb-2 fw-bold" style="font-size: 18px; margin-bottom:8px;">
                                    VIGENCIA CREDENCIAL
                                </h5>

                                <div class="row g-2 text-center">

                                    <!-- FECHA EMISION -->
                                    <div class="col-4" style="font-size: 18px; line-height:1.1;">
                                        <strong>Fecha Emisión:</strong><br>
                                        {{ $credencial?->fecha_emision?->format('d/m/Y') ?? 'En credencial antiguo' }}
                                    </div>

                                    <!-- FECHA VENCIMIENTO -->
                                    <div class="col-4" style="font-size: 18px; line-height:1.1;">
                                        <strong>Fecha Vencimiento:</strong><br>
                                        {{ $credencial?->fecha_vencimiento?->format('d/m/Y') ?? 'En credencial antiguo' }}
                                    </div>

                                    <!-- VIGENCIA BENEFICIARIO -->
                                    <div class="col-4" style="font-size: 18px; line-height:1.1;">

                                        <strong>Vig. Beneficiario:</strong><br>

                                        @if(class_basename($entidad) === 'BeneficiarioTitular')

                                            @if(in_array($entidad->parentesco, ['HIJO', 'HIJA', 'HIJASTRO', 'HIJASTRA']))

                                                {{ \Carbon\Carbon::parse($entidad->fecha_nacimiento)
                                                    ->addYears(29)
                                                    ->format('d-m-Y') }}

                                            @elseif(in_array($entidad->parentesco, ['ESPOSA', 'ESPOSO']))

                                                INDEFINIDO

                                            @else

                                                No definido

                                            @endif

                                        @else

                                            No aplica

                                        @endif

                                    </div>

                                </div>
                            </div><br>
                        @endif
                </div><br>

                <!-- Cuadro de datos del titular (solo si es beneficiario) -->
                    @if(class_basename($entidad) !== 'SocioTitular' && $entidad->titular)
                        <div class="p-4 text-center" style="background: linear-gradient(135deg, #1b263b, #415a77);">
                            <h5 class="text-center mb-4 fw-bold" style="font-size: 26px;">DATOS DEL SOCIO TITULAR</h5>
                            <div class="row g-3 text-center">
                                <div class="col-4" style="font-size: 20px;">
                                    <strong>GRADO:</strong><br>
                                    {{ $entidad->titular->grado ?? 'No registrado' }}
                                    {{ $entidad->titular->arma_especialidad ?? 'No registrado' }}
                                </div>
                                <div class="col-4" style="font-size: 20px;">
                                    <strong>NOMBRE:</strong><br>
                                    {{ $entidad->titular->nombres ?? 'No asociado' }} 
                                    {{ $entidad->titular->paterno ?? '' }} 
                                    {{ $entidad->titular->materno ?? '' }}
                                </div>
                                <div class="col-4" style="font-size: 20px;">
                                    <strong>CI:</strong><br>
                                    {{ $entidad->titular->ci ?? 'No registrado' }}
                                </div>
                            </div>
                        </div>
                    @endif
            @endif
        </div>

            <!-- Botón Cerrar Ventana - centrado y separado -->
        <div class="text-center mt-2 pt-2 pb-4">
            <button onclick="window.close()" class="btn btn-outline-light btn-lg px-5 py-3 shadow">
                <i class="bi bi-x-circle me-2"></i> Cerrar Ventana
            </button>
        </div>
    </div>


</div>
@endsection