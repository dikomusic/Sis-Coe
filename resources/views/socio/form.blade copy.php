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
        <input type="text" name="grado" class="form-control" value="{{ old('grado', $socio->grado ?? '') }}">
    </div>

    <div class="col-md-4 mb-3">
        <label>Arma / Especialidad</label>
        <input type="text" name="arma_especialidad" class="form-control" value="{{ old('arma_especialidad', $socio->arma_especialidad ?? '') }}">
    </div>

    <div class="col-md-4 mb-3">
        <label>Situación Actual</label>
        <input type="text" name="situacion_actual" class="form-control" value="{{ old('situacion_actual', $socio->situacion_actual ?? '') }}">
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
        <label>Tipo de Sangre</label>
        <input type="text" name="tipo_sangre" class="form-control" value="{{ old('tipo_sangre', $socio->tipo_sangre ?? '') }}">
    </div>

    <div class="col-md-4 mb-3">
        <label>Dirección</label>
        <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $socio->direccion ?? '') }}">
    </div>

    <div class="col-md-4 mb-3">
        <label>Teléfono Celular</label>
        <input type="text" name="telf_cel" class="form-control" value="{{ old('telf_cel', $socio->telf_cel ?? '') }}">
    </div>

    <div class="col-md-4 mb-3">
        <label>Destino</label>
        <input type="text" name="destino" class="form-control" value="{{ old('destino', $socio->destino ?? '') }}">
    </div>

    <div class="col-md-4 mb-3">
        <label>Fecha Alta Ejército</label>
        <input type="date" name="fecha_alta_ejercito" class="form-control" value="{{ old('fecha_alta_ejercito', $socio->fecha_alta_ejercito ?? '') }}">
    </div>

    <div class  class="col-md-4 mb-3">
        <label>Fecha Alta COE</label>
        <input type="date" name="fecha_alta_coe" class="form-control" value="{{ old('fecha_alta_coe', $socio->fecha_alta_coe ?? '') }}">
    </div>

    <div class="col-md-4 mb-3">
        <label>Estado Socio</label>
        <input type="text" name="estado_socio" class="form-control" value="{{ old('estado_socio', $socio->estado_socio ?? '') }}">
    </div>

        <!-- ====== CAMPO FOTO CON PREVIEW (reemplaza el anterior) ====== -->
    <div class="col-md-4 mb-3">
        <label for="foto" class="form-label">Foto del Socio</label>

        <!-- Contenedor de vista previa -->
        <div class="text-center mb-3">
            <div id="preview-container" style="position: relative; display: inline-block;">
                @if(isset($socio) && $socio->foto)
                    <img id="preview-img"
                         src="{{ asset('storage/' . $socio->foto) }}"
                         alt="Foto actual"
                         class="img-thumbnail shadow-sm"
                         style="width: 180px; height: 180px; object-fit: cover; border: 4px solid #007bff; border-radius: 12px;">
                @else
                    <img id="preview-img"
                         src="https://via.placeholder.com/180x180/e9ecef/6c757d?text=Sin+Foto"
                         alt="Sin foto"
                         class="img-thumbnail"
                         style="width: 180px; height: 180px; object-fit: cover; border: 3px dashed #ccc; border-radius: 12px;">
                @endif
            </div>

            <!-- Checkbox para eliminar foto (solo si ya tiene una) -->
            @if(isset($socio) && $socio->foto)
                <div class="mt-2">
                    <label class="text-danger" style="cursor: pointer; user-select: none;">
                        <input type="checkbox" name="borrar_foto" value="1" id="borrar_foto">
                        Eliminar foto actual
                    </label>
                </div>
            @endif
        </div>

        <!-- Input file -->
        <input type="file"
               name="foto"
               id="foto"
               class="form-control @error('foto') is-invalid @enderror"
               accept="image/jpeg,image/png,image/webp">

        @error('foto')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror

        <small class="text-muted d-block">JPG, PNG o WebP • Máx. 2MB</small>
    </div>
    <!-- ====== FIN CAMPO FOTO ====== -->

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