@props([
    'tarjeta' => null,
])

@php
    $inscripcion = $tarjeta->inscripcion;
    $persona = $inscripcion->persona;
    $horario = $inscripcion->horarioCurso;
    $disciplina = $horario?->disciplina;
    $instructor = $horario?->instructor;
@endphp

<!-- Cara Frontal (Anverso) -->
<div class="w-[88mm] h-[56mm] bg-gradient-to-br from-[#121214] via-[#1E1E24] to-[#121214] text-white rounded-2xl p-4 shadow-xl border border-[#FF0066]/40 relative overflow-hidden flex flex-col justify-between print:shadow-none print:border-black print:rounded-none">
    <!-- Adorno militar superior COE (Magenta + Oro) -->
    <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#FF0066]/15 rounded-full blur-2xl pointer-events-none"></div>
    <div class="absolute -bottom-10 -left-10 w-28 h-28 bg-[#EDD00A]/10 rounded-full blur-2xl pointer-events-none"></div>

    <!-- Cabecera Institucional con Escudo Oficial COE -->
    <div class="flex items-center justify-between border-b border-slate-700/80 pb-2 relative z-10">
        <div class="flex items-center gap-2.5">
            <img src="{{ asset('images/logo-coe.jpg') }}" alt="COE" class="h-8 w-8 rounded-lg object-cover shadow border border-[#FF0066]/60">
            <div>
                <h4 class="text-[10px] font-black uppercase tracking-wider text-white leading-tight">Círculo de Oficiales del Ejército</h4>
                <p class="text-[8px] font-bold text-[#FF0066] uppercase tracking-widest">Tarjeta de Control y Asistencia</p>
            </div>
        </div>
        <div class="text-right">
            <span class="text-[8px] font-mono font-bold bg-[#FF0066]/20 text-[#FF0066] border border-[#FF0066]/50 px-1.5 py-0.5 rounded">
                {{ $tarjeta->codigo_tarjeta }}
            </span>
        </div>
    </div>

    <!-- Datos del Alumno / Socio -->
    <div class="my-auto py-1 space-y-1 relative z-10">
        <div>
            <span class="text-[7px] uppercase font-bold text-slate-400 block tracking-wider">Alumno / Titular:</span>
            <p class="text-xs font-black text-white leading-tight uppercase truncate">
                {{ $persona->nombre_completo }}
            </p>
        </div>

        <div class="grid grid-cols-2 gap-2 text-[8px]">
            <div>
                <span class="text-slate-400 font-medium">CI / Carnet:</span>
                <span class="font-bold text-slate-200 block">{{ $persona->ci_completo }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-medium">Categoría:</span>
                <span class="font-bold text-[#EDD00A] block uppercase">{{ $persona->tipo_persona }}</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 text-[8px] pt-0.5">
            <div>
                <span class="text-slate-400 font-medium">Disciplina:</span>
                <span class="font-bold text-white block uppercase">{{ $disciplina?->nombre ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-medium">Turno:</span>
                <span class="font-bold text-slate-200 block">{{ substr($horario?->hora_inicio ?? '', 0, 5) }} - {{ substr($horario?->hora_fin ?? '', 0, 5) }}</span>
            </div>
        </div>
    </div>

    <!-- Pie: Fechas de Vigencia -->
    <div class="border-t border-slate-700/80 pt-1.5 flex items-center justify-between text-[7.5px] text-slate-300 relative z-10">
        <div>
            <span class="text-slate-400">Inicio:</span> 
            <strong class="text-white">{{ $inscripcion->fecha_inicio ? $inscripcion->fecha_inicio->format('d/m/Y') : '-' }}</strong>
        </div>
        <div>
            <span class="text-slate-400">Vencimiento:</span> 
            <strong class="text-[#EDD00A]">{{ $tarjeta->fecha_vencimiento ? $tarjeta->fecha_vencimiento->format('d/m/Y') : '-' }}</strong>
        </div>
        <div>
            <span class="text-slate-400">Inst:</span> 
            <span class="font-medium truncate max-w-[60px] inline-block align-bottom">{{ $instructor?->nombre_completo ?? 'COE' }}</span>
        </div>
    </div>
</div>
