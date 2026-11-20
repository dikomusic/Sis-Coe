<div class="row">
    <div class="col-md-4 mb-3">
        <label>Boleta de Pago</label>
        <input type="text" name="boleta_pago" class="form-control" value="{{ old('boleta_pago', $socio->boleta_pago ?? '') }}">
        @error('boleta_pago') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label>Código Cossmil</label>
        <input type="text" name="codigo_cossmil" class="form-control" value="{{ old('codigo_cossmil', $socio->codigo_cossmil ?? '') }}">
    </div>

    
<div class="col-md-4 mb-3">
    <label>Grado</label>
    <select name="grado" class="form-select" required>
         <option value="">-- Seleccionar Grado --</option>
         <option value="SBTTE." {{ old('grado', $socio->grado ?? '') == 'SBTTE.' ? 'selected' : '' }}>SBTTE.</option>
         <option value="TTE." {{ old('grado', $socio->grado ?? '') == 'TTE.' ? 'selected' : '' }}>TTE.</option>
         <option value="CAP." {{ old('grado', $socio->grado ?? '') == 'CAP.' ? 'selected' : '' }}>CAP.</option>
         <option value="MY." {{ old('grado', $socio->grado ?? '') == 'MY.' ? 'selected' : '' }}>MY.</option>
         <option value="TCNL." {{ old('grado', $socio->grado ?? '') == 'TCNL.' ? 'selected' : '' }}>TCNL.</option>
         <option value="CNL." {{ old('grado', $socio->grado ?? '') == 'CNL.' ? 'selected' : '' }}>CNL.</option>
         <option value="GRAL." {{ old('grado', $socio->grado ?? '') == 'GRAL.' ? 'selected' : '' }}>GRAL.</option>
    </select>
    @error('grado')
         <small class="text-danger">{{ $message }}</small>
        @enderror
</div>

    <div class="col-md-4 mb-3">
    <label class="form-label">Arma / Especialidad</label>
    
    <select name="arma_especialidad" class="form-select" id="armaSelect">
        <option value="">-- Seleccionar Arma / Especialidad --</option>
        <option value="INF." {{ old('arma_especialidad', $socio->arma_especialidad ?? '') == 'INF.' ? 'selected' : '' }}>INF.</option>
        <option value="ING." {{ old('arma_especialidad', $socio->arma_especialidad ?? '') == 'ING.' ? 'selected' : '' }}>ING.</option>
        <option value="CAB." {{ old('arma_especialidad', $socio->arma_especialidad ?? '') == 'CAB.' ? 'selected' : '' }}>CAB.</option>
        <option value="LOG." {{ old('arma_especialidad', $socio->arma_especialidad ?? '') == 'LOG.' ? 'selected' : '' }}>LOG.</option>
        <option value="ART." {{ old('arma_especialidad', $socio->arma_especialidad ?? '') == 'ART.' ? 'selected' : '' }}>ART.</option>
        <option value="COM." {{ old('arma_especialidad', $socio->arma_especialidad ?? '') == 'COM.' ? 'selected' : '' }}>COM.</option>
        <option value="DIM." {{ old('arma_especialidad', $socio->arma_especialidad ?? '') == 'DIM.' ? 'selected' : '' }}>DIM.</option>
        <option value="DEM." {{ old('arma_especialidad', $socio->arma_especialidad ?? '') == 'DEM.' ? 'selected' : '' }}>DEM.</option>
        <option value="DAEN." {{ old('arma_especialidad', $socio->arma_especialidad ?? '') == 'DAEN.' ? 'selected' : '' }}>DAEN.</option>
        <option value="DAENMG." {{ old('arma_especialidad', $socio->arma_especialidad ?? '') == 'DAENMG.' ? 'selected' : '' }}>DAENMG.</option>
        <option value="BRIG." {{ old('arma_especialidad', $socio->arma_especialidad ?? '') == 'BRIG.' ? 'selected' : '' }}>BRIG.</option>
        <option value="DIV." {{ old('arma_especialidad', $socio->arma_especialidad ?? '') == 'DIV.' ? 'selected' : '' }}>DIV.</option>
        <option value="EJTO." {{ old('arma_especialidad', $socio->arma_especialidad ?? '') == 'EJTO.' ? 'selected' : '' }}>EJTO.</option>
        <option value="OTRO">Otro (escribir manualmente)</option>
    </select>

    <!-- Campo oculto que aparece solo si elige "Otro" -->
    <input type="text" 
           name="arma_especialidad_manual" 
           id="armaManual" 
           class="form-control mt-2" 
           placeholder="Escribe aquí la arma/especialidad" 
           style="display: none;"
           value="{{ old('arma_especialidad_manual', 
               (old('arma_especialidad', $socio->arma_especialidad ?? '') && 
                !in_array(old('arma_especialidad', $socio->arma_especialidad ?? ''), 
                    ['ING.','CAB.','LOG.','ART.','COM.','DIM.','DEM.','DAEN.','DAENMG.','BRIG.','DIV.','EJTO.']) 
               ) ? old('arma_especialidad', $socio->arma_especialidad ?? '') : '') }}">

    @error('arma_especialidad')
        <small class="text-danger">{{ $message }}</small>
    @enderror
    @error('arma_especialidad_manual')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<!-- JavaScript para mostrar/ocultar el campo manual -->
<script>
document.getElementById('armaSelect').addEventListener('change', function() {
    const manualInput = document.getElementById('armaManual');
    if (this.value === 'OTRO') {
        manualInput.style.display = 'block';
        manualInput.required = true;
    } else {
        manualInput.style.display = 'none';
        manualInput.required = false;
        manualInput.value = '';
    }
});

// Si al cargar la página ya estaba seleccionado "OTRO", mostrar el campo
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('armaSelect');
    const manualInput = document.getElementById('armaManual');
    @if(old('arma_especialidad', $socio->arma_especialidad ?? '') && 
        !in_array(old('arma_especialidad', $socio->arma_especialidad ?? ''), 
            ['ING.','CAB.','LOG.','ART.','COM.','DIM.','DEM.','DAEN.','DAENMG.','BRIG.','DIV.','EJTO.']))
        select.value = 'OTRO';
        manualInput.style.display = 'block';
        manualInput.required = true;
    @endif
});
</script>
    <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Situación Actual</label>
        <select name="situacion_actual" class="form-select" required>
            <option value="">-- Seleccionar Situación --</option>
            <option value="ACTIVO" class="text-success fw-bold"
                {{ old('situacion_actual', $socio->situacion_actual ?? '') == 'ACTIVO' ? 'selected' : '' }}>
                ACTIVO
            </option>
            <option value="RESERVA ACTIVA" class="text-primary"
                {{ old('situacion_actual', $socio->situacion_actual ?? '') == 'RESERVA ACTIVA' ? 'selected' : '' }}>
                RESERVA ACTIVA
            </option>
            <option value="PASIVO" class="text-warning"
                {{ old('situacion_actual', $socio->situacion_actual ?? '') == 'PASIVO' ? 'selected' : '' }}>
                PASIVO
            </option>
            <option value="PASIVO(+)" class="text-danger"
                {{ old('situacion_actual', $socio->situacion_actual ?? '') == 'PASIVO(+)' ? 'selected' : '' }}>
                PASIVO (+)
            </option>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label>Paterno</label>
        <input type="text" name="paterno" class="form-control" value="{{ old('paterno', $socio->paterno ?? '') }}">
    </div>

    <div class="col-md-4 mb-3">
        <label>Materno</label>
        <input type="text" name="materno" class="form-control" value="{{ old('materno', $socio->materno ?? '') }}">
    </div>

    <div class="col-md-4 mb-3">
        <label>Nombres</label>
        <input type="text" name="nombres" class="form-control" value="{{ old('nombres', $socio->nombres ?? '') }}">
    </div>

    <div class="col-md-4 mb-3">
        <label>CI</label>
        <input type="text" name="ci" class="form-control" value="{{ old('ci', $socio->ci ?? '') }}">
    </div>

    <div class="col-md-4 mb-3">
        <label>Fecha de Nacimiento</label>
        <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento', $socio->fecha_nacimiento ?? '') }}">
    </div>

    <div class="col-md-4 mb-3">
    <label class="form-label">Tipo de Sangre</label>
    <select name="tipo_sangre" class="form-select" required>
        <option value="">-- Seleccionar Tipo de Sangre --</option>
        <option value="O RH(+)" {{ old('tipo_sangre', $socio->tipo_sangre ?? '') == 'O RH(+)' ? 'selected' : '' }}>O RH(+)</option>
        <option value="O RH(-)" {{ old('tipo_sangre', $socio->tipo_sangre ?? '') == 'O RH(-)' ? 'selected' : '' }}>O RH(-)</option>
        <option value="A RH(+)" {{ old('tipo_sangre', $socio->tipo_sangre ?? '') == 'A RH(+)' ? 'selected' : '' }}>A RH(+)</option>
        <option value="A RH(-)" {{ old('tipo_sangre', $socio->tipo_sangre ?? '') == 'A RH(-)' ? 'selected' : '' }}>A RH(-)</option>
        <option value="B RH(+)" {{ old('tipo_sangre', $socio->tipo_sangre ?? '') == 'B RH(+)' ? 'selected' : '' }}>B RH(+)</option>
        <option value="B RH(-)" {{ old('tipo_sangre', $socio->tipo_sangre ?? '') == 'B RH(-)' ? 'selected' : '' }}>B RH(-)</option>
        <option value="AB RH(+)" {{ old('tipo_sangre', $socio->tipo_sangre ?? '') == 'AB RH(+)' ? 'selected' : '' }}>AB RH(+)</option>
        <option value="AB RH(-)" {{ old('tipo_sangre', $socio->tipo_sangre ?? '') == 'AB RH(-)' ? 'selected' : '' }}>AB RH(-)</option>
        <option value="ACT. GPO." {{ old('tipo_sangre', $socio->tipo_sangre ?? '') == 'ACT. GPO.' ? 'selected' : '' }}>ACT. GPO.</option>
    </select>

    @error('tipo_sangre')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
    <div class="col-md-4 mb-3">
        <label>Dirección</label>
        <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $socio->direccion ?? '') }}">
    </div>

    <!-- ==================== FILA ESPECIAL: 2 filas + foto grande ==================== -->
                <div class="row g-4 mb-5">

                    <!-- Columna 1: Fecha Alta Ejército + Teléfono Celular + Estado Socio-->
                    <div class="col-md-4">
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Teléfono Celular</label>
                            <input type="text" name="telf_cel" class="form-control"
                                   value="{{ old('telf_cel', $socio->telf_cel ?? '') }}">
                        </div>
                        <div>
                            <label class="form-label fw-semibold">Fecha Alta Ejército</label>
                            <input type="date" name="fecha_alta_ejercito" class="form-control"
                                   value="{{ old('fecha_alta_ejercito', $socio->fecha_alta_ejercito ?? '') }}">
                        </div>
                        <div class="col-md-4">
                        <label class="form-label fw-semibold">Estado Socio</label>
                        <select name="estado_socio" class="form-select">
                            <option value="Activo" {{ old('estado_socio', $socio->estado_socio ?? '') == 'Activo' ? 'selected' : '' }}>Activo</option>
                            <option value="Inactivo" {{ old('estado_socio', $socio->estado_socio ?? '') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        
                    </div>

                    </div>

                    <!-- Columna 2: Fecha Alta COE + Destino -->
                    <div class="col-md-4">
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Destino</label>
                            <input type="text" name="destino" class="form-control"
                                   value="{{ old('destino', $socio->destino ?? '') }}">
                        </div>
                        <div>
                            <label class="form-label fw-semibold">Fecha Alta COE</label>
                            <input type="date" name="fecha_alta_coe" class="form-control"
                                   value="{{ old('fecha_alta_coe', $socio->fecha_alta_coe ?? '') }}">
                        </div>
                    </div>

                    <!-- Columna 3: FOTO que ocupa toda la altura -->
                    <div class="col-md-4 d-flex flex-column justify-content-start">
                        <label class="form-label fw-semibold mb-3">Foto del Socio</label>

                        <div class="text-center flex-grow-1">
                            <div id="preview-container" style="position: relative; display: inline-block;">
                                @if(isset($socio) && $socio->foto)
                                    <img id="preview-img"
                                         src="{{ asset('storage/' . $socio->foto) }}"
                                         alt="Foto actuali"
                                         class="img-thumbnail shadow-sm"
                                         style="width: 180px; height: 180px; object-fit: cover; border: 4px solid #0d6efd; border-radius: 12px;">
                                @else
                                    <img id="preview-img"
                                         src="https://via.placeholder.com/180x180/e9ecef/6c757d?text=Sin+Foto"
                                         alt="Sin foto"
                                         class="img-thumbnail"
                                         style="width: 180px; height: 180px; object-fit: cover; border: 3px dashed #ccc; border-radius: 12px;">
                                @endif
                            </div>

                            @if(isset($socio) && $socio->foto)
                                <div class="mt-2">
                                    <label class="text-danger" style="cursor: pointer; user-select: none;">
                                        <input type="checkbox" name="borrar_foto" value="1" id="borrar_foto">
                                        Eliminar foto actual
                                    </label>
                                </div>
                            @endif
                        </div>

                        <input type="file" name="foto" id="foto" class="form-control mt-3"
                               accept="image/jpeg,image/png,image/webp">
                        @error('foto') <small class="text-danger">{{ $message }}</small> @enderror
                        <small class="text-muted d-block">JPG, PNG o WebP • Máx. 2MB</small>
                    </div>

                     
                </div>
                <!-- ==================== FIN FILA ESPECIAL ==================== -->



</div>

<!-- Script para preview en tiempo real (va al final del archivo) -->
<script>
document.getElementById('foto')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('preview-img');
    const borrarCheckbox = document.getElementById('borrar_foto');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.border = '4px solid #28a745';
            preview.style.borderRadius = '12px';

            // Si selecciona nueva foto, desmarcar "borrar" y ocultar el checkbox
            if (borrarCheckbox) {
                borrarCheckbox.checked = false;
                borrarCheckbox.parentElement.style.display = 'none';
            }
        }
        reader.readAsDataURL(file);
    }
});

// Si marcan "eliminar foto"
document.getElementById('borrar_foto')?.addEventListener('change', function() {
    if (this.checked) {
        document.getElementById('preview-img').src = 'https://via.placeholder.com/180x180/e9ecef/6c757d?text=Sin+Foto';
        document.getElementById('preview-img').style.border = '3px dashed #ccc';
    }
});
</script>