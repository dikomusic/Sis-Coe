@extends('templates.erp-layout', ['title' => 'Ventanilla de Cobro e Inscripción'])

@section('content')
<div 
    x-data="ventanillaApp()" 
    x-init="init()"
    class="max-w-5xl mx-auto space-y-6"
>
    <!-- Encabezado de Ventanilla -->
    <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4">
        <div>
            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Emisión de Inscripción y Tarjeta COE</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Complete los 3 pasos para generar el comprobante y la tarjeta física de control.</p>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-slate-500">Recibo Sugerido:</span>
            <span class="font-mono text-xs font-bold bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-slate-200 px-2 py-1 rounded-md">
                {{ $numeroFacturaSugerido }}
            </span>
        </div>
    </div>

    <!-- Bandeja de Cobro Familiar / Lote (Multipersona: Hermanos, grupos o compras en lote) -->
    @include('organisms.batch-tray')

    <!-- Formulario Principal -->
    <form action="{{ route('cajas.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Organismo Global: Paso 1 - Selector de Persona -->
        @include('organisms.step-person-selector')

        <!-- Organismo Global: Paso 2 - Selector de Disciplina y Turno -->
        @include('organisms.step-course-selector')

        <!-- Organismo Global: Paso 3 - Facturación y Totales -->
        @include('organisms.step-billing-box')
    </form>

    <!-- Modal de Alta Rápida de Particular -->
    <div 
        x-show="abrirModalParticular" 
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
        x-cloak
    >
        <div 
            @click.away="abrirModalParticular = false"
            class="bg-white dark:bg-slate-900 rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-4"
        >
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                <h3 class="font-bold text-lg text-slate-900 dark:text-white">Registrar Nuevo Alumno Particular</h3>
                <button type="button" @click="abrirModalParticular = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Nombres *</label>
                        <input type="text" x-model="nuevoParticular.nombres" placeholder="Nombres" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2 px-3">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Apellidos *</label>
                        <input type="text" x-model="nuevoParticular.apellidos" placeholder="Apellidos" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2 px-3">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Carnet de Identidad (CI) *</label>
                        <input type="text" x-model="nuevoParticular.ci" placeholder="Número CI" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2 px-3">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Expedido</label>
                        <select x-model="nuevoParticular.expedido" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2 px-3">
                            <option value="LP">LP - La Paz</option>
                            <option value="CB">CB - Cochabamba</option>
                            <option value="SC">SC - Santa Cruz</option>
                            <option value="OR">OR - Oruro</option>
                            <option value="PT">PT - Potosí</option>
                            <option value="CH">CH - Chuquisaca</option>
                            <option value="TJ">TJ - Tarija</option>
                            <option value="BN">BN - Beni</option>
                            <option value="PA">PA - Pando</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Teléfono / Celular</label>
                        <input type="text" x-model="nuevoParticular.telefono" placeholder="Ej. 71234567" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2 px-3">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Correo Electrónico</label>
                        <input type="email" x-model="nuevoParticular.email" placeholder="correo@ejemplo.com" class="block w-full rounded-xl border-slate-300 sm:text-xs py-2 px-3">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" @click="abrirModalParticular = false" class="px-3 py-1.5 rounded-xl text-xs font-bold border border-slate-300 hover:bg-slate-100 transition-colors">
                    Cancelar
                </button>
                <button type="button" @click="guardarNuevoParticular()" :disabled="guardandoParticular" class="px-4 py-1.5 rounded-xl text-xs font-bold bg-[#FF0066] hover:bg-[#E6005C] text-white shadow-md shadow-[#FF0066]/20 transition-colors flex items-center gap-1.5">
                    <template x-if="guardandoParticular">
                        @include('atoms.spinner', ['size' => 'sm', 'color' => 'white'])
                    </template>
                    <span>Guardar y Seleccionar</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function ventanillaApp() {
        return {
            busquedaPersona: '',
            cargando: false,
            resultados: [],
            personaSeleccionada: @json($personaPreseleccionada ?? null),

            disciplinaSeleccionadaId: null,
            cargandoHorarios: false,
            horariosDisponibles: [],
            horarioSeleccionado: null,
            turnosPorDisciplina: @json($horariosPorDisciplina ?? []),

            tarifaId: '',
            fechaInicio: '{{ date("Y-m-d") }}',
            numeroFactura: '{{ $numeroFacturaSugerido }}',
            descuentoPorcentaje: 0,
            motivoDescuento: '',
            autorizacionDescuento: '',

            importeBase: 0,
            montoDescuento: 0,
            importeFinal: 0,
            tipoBeneficio: 'NINGUNO',

            // Estado de Tarjeta Vigente Activa
            tarjetaVigenteExonerada: false,

            // Estado de Bandeja POS Familiar / Lote
            carrito: [],
            cobrandoLote: false,
            modalLoteExitoso: false,
            loteResultado: null,

            abrirModalParticular: false,
            guardandoParticular: false,
            nuevoParticular: {
                nombres: '',
                apellidos: '',
                ci: '',
                expedido: 'LP',
                telefono: '',
                email: ''
            },

            init() {
                @if(isset($personaPreseleccionada) && $personaPreseleccionada)
                    this.seleccionarPersona(@json($personaPreseleccionada));
                    @if(isset($personaPreseleccionada['ultimo_curso']) && $personaPreseleccionada['ultimo_curso'])
                        this.renovarUltimoCurso();
                    @endif
                @elseif(count($disciplinas) > 0)
                    this.seleccionarDisciplina({{ $disciplinas[0]->id }}, '{{ addslashes($disciplinas[0]->nombre) }}');
                @endif
            },

            renovarUltimoCurso() {
                if (!this.personaSeleccionada || !this.personaSeleccionada.ultimo_curso) return;
                const uc = this.personaSeleccionada.ultimo_curso;
                if (uc.disciplina_id) {
                    this.seleccionarDisciplina(uc.disciplina_id, uc.disciplina_nombre);
                    this.$nextTick(() => {
                        if (uc.horario_id) {
                            const h = this.horariosDisponibles.find(x => x.id == uc.horario_id);
                            if (h) this.seleccionarHorario(h);
                        }
                        if (uc.tarifa_id) {
                            this.tarifaId = uc.tarifa_id;
                            this.actualizarMontoTarifa();
                        }
                    });
                }
            },

            get formularioValido() {
                return this.personaSeleccionada && 
                       this.horarioSeleccionado && 
                       this.tarifaId && 
                       this.numeroFactura.trim().length > 0 &&
                       this.fechaInicio;
            },

            get itemValidoParaCarrito() {
                return this.personaSeleccionada && 
                       this.horarioSeleccionado && 
                       this.tarifaId && 
                       this.fechaInicio;
            },

            get totalLote() {
                return this.carrito.reduce((acc, curr) => acc + (parseFloat(curr.importe_final) || 0), 0);
            },

            async buscar() {
                const q = this.busquedaPersona.trim();
                if (q.length < 2) {
                    this.resultados = [];
                    return;
                }
                this.cargando = true;
                try {
                    const res = await fetch(`{{ route('cajas.api.buscarPersona') }}?q=${encodeURIComponent(q)}`);
                    this.resultados = await res.json();
                } catch (e) {
                    console.error('Error al buscar:', e);
                } finally {
                    this.cargando = false;
                }
            },

            seleccionarPersona(persona) {
                this.personaSeleccionada = persona;
                this.resultados = [];
                this.busquedaPersona = '';

                if (persona.es_socio) {
                    this.tipoBeneficio = 'SOCIO_TITULAR';
                } else if (persona.es_beneficiario) {
                    this.tipoBeneficio = 'BENEFICIARIO_FAMILIAR';
                } else {
                    this.tipoBeneficio = 'PARTICULAR';
                }

                // Si tiene tarjeta físicamente activa, marcar exoneración por defecto (Regla COE)
                if (persona.tarjeta_activa) {
                    this.tarjetaVigenteExonerada = true;
                    this.toggleExoneracionTarjetaVigente();
                } else {
                    this.tarjetaVigenteExonerada = false;
                    this.descuentoPorcentaje = 0;
                    this.motivoDescuento = '';
                    this.autorizacionDescuento = '';
                }

                this.autoSeleccionarTarifa();
                this.recalcularTotales();
            },

            deseleccionarPersona() {
                this.personaSeleccionada = null;
                this.tipoBeneficio = 'NINGUNO';
                this.tarjetaVigenteExonerada = false;
                this.descuentoPorcentaje = 0;
                this.motivoDescuento = '';
                this.autorizacionDescuento = '';
                this.recalcularTotales();
            },

            toggleExoneracionTarjetaVigente() {
                if (this.tarjetaVigenteExonerada) {
                    this.descuentoPorcentaje = 100;
                    this.motivoDescuento = 'Tarjeta física vigente activa (Regla COE: Renovación no se cobra)';
                    this.autorizacionDescuento = 'Regla COE Institucional';
                } else {
                    this.descuentoPorcentaje = 0;
                    this.motivoDescuento = '';
                    this.autorizacionDescuento = '';
                }
                this.recalcularTotales();
            },

            aplicarExoneracionTarjetaVigente() {
                this.tarjetaVigenteExonerada = !this.tarjetaVigenteExonerada;
                this.toggleExoneracionTarjetaVigente();
            },

            agregarAlCarrito() {
                if (!this.itemValidoParaCarrito) {
                    alert('Complete la selección de persona, turno y tarifa antes de agregar al lote.');
                    return;
                }

                const selectTarifa = document.querySelector('select[name="tarifa_id"]');
                const tarifaTexto = selectTarifa?.options[selectTarifa.selectedIndex]?.text || 'Tarifa';
                const discBtn = document.querySelector('button[data-disciplina-id="' + this.disciplinaSeleccionadaId + '"]');
                const discNombre = discBtn ? discBtn.innerText.trim() : 'Curso';

                const item = {
                    id_temp: Date.now() + Math.random(),
                    persona_id: this.personaSeleccionada.id,
                    persona_nombre: this.personaSeleccionada.nombre_completo,
                    persona_ci: this.personaSeleccionada.ci,
                    persona_tipo: this.personaSeleccionada.tipo_persona,
                    disciplina_id: this.disciplinaSeleccionadaId,
                    disciplina_nombre: discNombre,
                    horario_curso_id: this.horarioSeleccionado.id,
                    horario_texto: `${this.horarioSeleccionado.rango_horario || ''} (${this.horarioSeleccionado.dias || ''}) - ${this.horarioSeleccionado.instructor || ''}`,
                    tarifa_id: this.tarifaId,
                    tarifa_nombre: tarifaTexto,
                    fecha_inicio: this.fechaInicio,
                    importe_base: this.importeBase,
                    descuento_porcentaje: this.descuentoPorcentaje,
                    monto_descuento: this.montoDescuento,
                    importe_final: this.importeFinal,
                    tipo_beneficio: this.tipoBeneficio,
                    motivo_descuento: this.motivoDescuento,
                    autorizacion_descuento: this.autorizacionDescuento,
                    tarjeta_vigente: this.personaSeleccionada.tarjeta_activa || false
                };

                this.carrito.push(item);

                // Limpiar selección actual para que el cajero busque al siguiente familiar de inmediato
                this.personaSeleccionada = null;
                this.horarioSeleccionado = null;
                this.descuentoPorcentaje = 0;
                this.motivoDescuento = '';
                this.autorizacionDescuento = '';
                this.tarjetaVigenteExonerada = false;
                this.recalcularTotales();

                // Foco en el buscador
                this.$nextTick(() => {
                    const inputBusqueda = document.querySelector('input[placeholder*="Buscar por CI"]');
                    if (inputBusqueda) inputBusqueda.focus();
                });
            },

            quitarDelCarrito(idx) {
                this.carrito.splice(idx, 1);
            },

            vaciarCarrito() {
                if (confirm('¿Desea vaciar todos los alumnos de la bandeja?')) {
                    this.carrito = [];
                }
            },

            async cobrarLote() {
                if (this.carrito.length === 0) return;
                if (!this.numeroFactura.trim()) {
                    alert('Debe ingresar el número de factura o recibo oficial.');
                    return;
                }

                this.cobrandoLote = true;
                try {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const res = await fetch('{{ route("cajas.store-lote") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            numero_factura: this.numeroFactura,
                            items: this.carrito
                        })
                    });

                    const data = await res.json();
                    if (data.success) {
                        this.loteResultado = data.data;
                        this.modalLoteExitoso = true;
                        this.carrito = [];
                    } else {
                        alert(data.message || 'Error al procesar el lote.');
                    }
                } catch (e) {
                    console.error('Error al cobrar lote:', e);
                    alert('Error en el servidor al procesar el lote.');
                } finally {
                    this.cobrandoLote = false;
                }
            },

            cerrarModalLoteExitoso() {
                this.modalLoteExitoso = false;
                this.loteResultado = null;
                const num = parseInt(this.numeroFactura);
                if (!isNaN(num)) {
                    this.numeroFactura = (num + 1).toString();
                }
            },

            async seleccionarDisciplina(id, nombre) {
                this.disciplinaSeleccionadaId = id;
                this.horarioSeleccionado = null;
                
                // Carga instantánea desde memoria local (0 ms)
                if (this.turnosPorDisciplina && this.turnosPorDisciplina[id] && this.turnosPorDisciplina[id].length > 0) {
                    this.horariosDisponibles = this.turnosPorDisciplina[id];
                    return;
                }
                
                // Fallback a API
                this.cargandoHorarios = true;
                try {
                    const res = await fetch(`{{ url('/cajas/api/disciplinas') }}/${id}/horarios`);
                    if (res.ok) {
                        this.horariosDisponibles = await res.json();
                        if (!this.turnosPorDisciplina) this.turnosPorDisciplina = {};
                        this.turnosPorDisciplina[id] = this.horariosDisponibles;
                    } else {
                        this.horariosDisponibles = [];
                    }
                } catch (e) {
                    console.error('Error al cargar horarios:', e);
                    this.horariosDisponibles = [];
                } finally {
                    this.cargandoHorarios = false;
                }
            },

            seleccionarHorario(horario) {
                this.horarioSeleccionado = horario;
                this.autoSeleccionarTarifa();
            },

            autoSeleccionarTarifa() {
                const select = document.querySelector('select[name="tarifa_id"]');
                if (select && select.options.length > 1) {
                    let matchingOption = null;
                    const tipoBuscado = this.personaSeleccionada?.es_socio ? 'SOCIO' : (this.personaSeleccionada?.es_beneficiario ? 'BENEFICIARIO' : 'PARTICULAR');
                    
                    for (let opt of select.options) {
                        if (opt.value && opt.text.toUpperCase().includes(tipoBuscado)) {
                            matchingOption = opt;
                            break;
                        }
                    }
                    if (matchingOption) {
                        this.tarifaId = matchingOption.value;
                        this.importeBase = parseFloat(matchingOption.getAttribute('data-costo') || 0);
                        this.recalcularTotales();
                    }
                }
            },

            actualizarMontoTarifa() {
                const select = document.querySelector('select[name="tarifa_id"]');
                const option = select?.options[select.selectedIndex];
                const costo = option ? parseFloat(option.getAttribute('data-costo') || 0) : 0;
                this.importeBase = costo;
                this.recalcularTotales();
            },

            recalcularTotales() {
                const pct = Math.max(0, Math.min(100, parseFloat(this.descuentoPorcentaje) || 0));
                this.montoDescuento = (this.importeBase * (pct / 100));
                this.importeFinal = Math.max(0, this.importeBase - this.montoDescuento);
            },

            async guardarNuevoParticular() {
                if (!this.nuevoParticular.nombres || !this.nuevoParticular.apellidos || !this.nuevoParticular.ci) {
                    alert('Complete los campos obligatorios: Nombres, Apellidos y CI.');
                    return;
                }

                this.guardandoParticular = true;
                try {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const res = await fetch('{{ route("cajas.api.registrarParticular") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(this.nuevoParticular)
                    });

                    const data = await res.json();
                    if (data.success) {
                        this.seleccionarPersona(data.persona);
                        this.abrirModalParticular = false;
                        this.nuevoParticular = { nombres: '', apellidos: '', ci: '', expedido: 'LP', telefono: '', email: '' };
                    } else {
                        alert(data.message || 'Error al registrar particular');
                    }
                } catch (e) {
                    alert('Error en el servidor al registrar');
                } finally {
                    this.guardandoParticular = false;
                }
            }
        }
    }
</script>
@endpush
@endsection
