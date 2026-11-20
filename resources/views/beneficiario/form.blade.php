<div class="row g-3">

    <!-- ==================== FOTO DEL BENEFICIARIO ==================== -->
    <div class="col-md-4 d-flex flex-column justify-content-start">
        <label class="form-label fw-semibold mb-3">Foto del Beneficiario</label>

        <div class="text-center flex-grow-1">
            <div id="preview-container">
                @if(isset($beneficiario) && $beneficiario->foto)
                    <img id="preview-img" src="{{ asset('storage/' . $beneficiario->foto) }}"
                         class="img-thumbnail shadow-sm"
                         style="width: 180px; height: 180px; object-fit: cover; border: 4px solid #0d6efd; border-radius: 12px;">
                @else
                    <img id="preview-img" src="https://via.placeholder.com/180x180/e9ecef/6c757d?text=Sin+Foto"
                         class="img-thumbnail"
                         style="width: 180px; height: 180px; object-fit: cover; border: 3px dashed #ccc; border-radius: 12px;">
                @endif
            </div>

            @if(isset($beneficiario) && $beneficiario->foto)
                <div class="mt-2">
                    <label class="text-danger" style="cursor: pointer;">
                        <input type="checkbox" name="borrar_foto" value="1" id="borrar_foto">
                        Eliminar foto actual
                    </label>
                </div>
            @endif
        </div>

        <div id="foto-input-container">
            <input type="file" name="foto" id="foto" class="form-control mt-3" accept="image/*">
            @error('foto') <small class="text-danger">{{ $message }}</small> @enderror
            <small class="text-muted d-block">JPG, PNG o WebP • Máx. 2MB</small>
        </div>
    </div>

    <!-- ==================== CAMPOS DEL FORMULARIO ==================== -->
    <div class="col-md-8">

        <!-- ID Titular (solo lectura) -->
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Socio Titular (ID)</label>
                <input type="text" class="form-control" value="{{ $socio->id_titular }}" readonly>
                <input type="hidden" name="id_titular" value="{{ $socio->id_titular }}">
            </div>
             <div class="col-md-4">
                <label class="form-label fw-semibold">Codigo Cossmil</label>
                <input type="text" name="codigo_cossmil" class="form-control" value="{{ old('codigo_cossmil', $beneficiario->codigo_cossmil ?? '') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Parentesco</label>
                <select name="parentesco" class="form-select" required>
                    <option value="">-- Seleccionar --</option>
                    @foreach(['ESPOSA','ESPOSO','HIJO','HIJA','HIJASTRO','HIJASTRA','OTRO'] as $p)
                        <option value="{{ $p }}" {{ old('parentesco', $beneficiario->parentesco ?? '') == $p ? 'selected' : '' }}>
                            {{ $p }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <!-- Fila nombres -->
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Paterno</label>
                <input type="text" name="paterno" class="form-control" value="{{ old('paterno', $beneficiario->paterno ?? '') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Materno</label>
                <input type="text" name="materno" class="form-control" value="{{ old('materno', $beneficiario->materno ?? '') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Nombres</label>
                <input type="text" name="nombres" class="form-control" value="{{ old('nombres', $beneficiario->nombres ?? '') }}" required>
            </div>
        </div>

        <!-- Fila datos personales -->
        <div class="row g-3 mt-2">
            <div class="col-md-3">
                <label class="form-label fw-semibold">CI</label>
                <input type="text" name="ci" class="form-control" value="{{ old('ci', $beneficiario->ci ?? '') }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Fecha Nacimiento</label>
                <input type="date" name="fecha_nacimiento" class="form-control"
                       value="{{ old('fecha_nacimiento', optional($beneficiario)->fecha_nacimiento?->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Tipo de Sangre</label>
                <select name="tipo_sangre" class="form-select">
                    <option value="">-- Seleccionar --</option>
                    @foreach(['O RH(+)', 'O RH(-)', 'A RH(+)', 'A RH(-)', 'B RH(+)', 'B RH(-)', 'AB RH(+)', 'AB RH(-)'] as $tipo)
                        <option value="{{ $tipo }}" {{ old('tipo_sangre', $beneficiario->tipo_sangre ?? '') == $tipo ? 'selected' : '' }}>
                            {{ $tipo }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Teléfono Celular</label>
                <input type="text" name="telf_cel" class="form-control" value="{{ old('telf_cel', $beneficiario->telf_cel ?? '') }}">
            </div>
        </div>

        <!-- Fila adicionales -->
        <div class="row g-3 mt-2">
            
            <div class="col-md-3">
                <label class="form-label fw-semibold">Fecha Alta COE</label>
                <input type="date" name="fecha_alta_coe" class="form-control" 
                    value="{{ old('fecha_alta_coe', 
                            $beneficiario->exists 
                                ? $beneficiario->fecha_alta_coe?->format('Y-m-d') 
                                : now()->format('Y-m-d')
                    ) }}">
            
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Estado del Beneficiario <span class="text-danger">*</span></label>
                <select name="estado_beneficiario" class="form-select" id="estado_beneficiario" required>
                    <option value="">-- Seleccionar Estado --</option>
                    <option value="ACTIVO" {{ old('estado_beneficiario', $beneficiario->estado_beneficiario ?? '') == 'ACTIVO' ? 'selected' : '' }}>
                        ACTIVO
                    </option>
                    <option value="INACTIVO" {{ old('estado_beneficiario', $beneficiario->estado_beneficiario ?? '') == 'INACTIVO' ? 'selected' : '' }}>
                        INACTIVO
                    </option>
                    <option value="OBSERVADO" {{ old('estado_beneficiario', $beneficiario->estado_beneficiario ?? '') == 'OBSERVADO' ? 'selected' : '' }}>
                        OBSERVADO
                    </option>
                    <option value="BAJA" {{ old('estado_beneficiario', $beneficiario->estado_beneficiario ?? '') == 'BAJA' ? 'selected' : '' }}>
                        BAJA
                    </option>
                </select>
            </div>

            <!-- CAMPO OBSERVACIÓN DINÁMICO --> 
            <div class="col-md-6">
                <label class="form-label fw-semibold">Observación <span class="text-danger">*</span></label>
                <select name="observacion" class="form-select" id="observacion_select" required>
                    <option value="">-- Seleccione primero un estado --</option>
                </select>
            </div>

            <!-- SCRIPT ALPINE.JS + DATOS (todo en una línea, sin dependencias externas) -->
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                const estadoSelect = document.getElementById('estado_beneficiario');
                const obsSelect = document.getElementById('observacion_select');

                // === TODAS LAS OBSERVACIONES POR ESTADO ===
                const observaciones = {
                    ACTIVO: [
                        'Sin observaciones.',
                        'Registro verificado.',
                        'Situación regularizada.'
                    ],
                    INACTIVO: [
                        'Titular con observación administrativa.',
                        'Falta de aporte obligatorio.',
                        'Pendiente de regularización de aportes.',
                        'Inactividad por incumplimiento de requisitos.',
                        'Motivo adicional.'
                    ],
                    BAJA: [
                        'Baja por desvinculación familiar (divorcio).',
                        'Baja según normativa de edad vigente.',
                        'Baja por fallecimiento.',
                        'Motivo adicional.'
                    ],
                    OBSERVADO: [
                        'Pendiente de presentación de documentación requerida.',
                        'Documentación incompleta.',
                        'Observación por falta de respaldo documental.',
                        'Motivo adicional.'
                    ]
                };

                // Valor guardado o viejo (para edit o errores)
                const valorGuardado = "{{ old('observacion', $beneficiario->observacion ?? '') }}";

                function actualizarObservaciones() {
                    const estado = estadoSelect.value;
                    obsSelect.innerHTML = '<option value="">-- Seleccionar observación --</option>';

                    if (observaciones[estado]) {
                        observaciones[estado].forEach(obs => {
                            const option = document.createElement('option');
                            option.value = obs;
                            option.textContent = obs;
                            if (obs === valorGuardado) option.selected = true;
                            obsSelect.appendChild(option);
                        });
                    }

                    // Si no hay estado seleccionado → deshabilitar
                    obsSelect.required = !!estado;
                }

                // Ejecutar al cargar
                actualizarObservaciones();

                // Ejecutar cada vez que cambie el estado
                estadoSelect.addEventListener('change', actualizarObservaciones);
            });
            </script>

        </div>

        <div class="mt-3">
            <label class="form-label fw-semibold">Nota</label>
            <textarea name="nota" class="form-control" rows="3">{{ old('nota', $beneficiario->nota ?? '') }}</textarea>
        </div>
    </div>
</div>

<!-- SCRIPT FINAL (sin errores y sin validación de foto cuando se borra) -->
<script>
document.getElementById('borrar_foto')?.addEventListener('change', function() {
    const preview = document.getElementById('preview-img');
    const container = document.getElementById('foto-input-container');
    const input = document.getElementById('foto');

    if (this.checked) {
        preview.src = 'https://via.placeholder.com/180x180/e9ecef/6c757d?text=Sin+Foto';
        preview.style.border = '3px dashed #ccc';
        input.removeAttribute('name');  // ← Laravel NO recibe el campo
        container.style.opacity = '0.5';
    } else {
        @if(isset($beneficiario) && $beneficiario->foto)
            preview.src = "{{ asset('storage/' . $beneficiario->foto) }}";
        @else
            preview.src = 'https://via.placeholder.com/180x180/e9ecef/6c757d?text=Sin+Foto';
        @endif
        preview.style.border = '4px solid #0d6efd';
        input.setAttribute('name', 'foto');
        container.style.opacity = '1';
    }
});

document.getElementById('foto')?.addEventListener('change', function() {
    if (this.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('preview-img').style.border = '4px solid #28a745';
            const borrar = document.getElementById('borrar_foto');
            if (borrar) borrar.checked = false;
            this.setAttribute('name', 'foto');
            document.getElementById('foto-input-container').style.opacity = '1';
        }
        reader.readAsDataURL(this.files[0]);
    }
});
</script>