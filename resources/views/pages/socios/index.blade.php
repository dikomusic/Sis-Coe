@extends('templates.erp-layout', ['title' => 'Gestión de Socios Titulares'])

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Socios Titulares del COE</h2>
            <p class="text-xs text-slate-500 mt-0.5">Padrón oficial de oficiales socios, grados, situaciones y beneficiarios.</p>
        </div>

        <a href="{{ route('admin.socio.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/25 transition-all">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>+ Registrar Nuevo Socio</span>
        </a>
    </div>

    <!-- Barra de Filtros y Búsqueda -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <form method="GET" action="{{ route('admin.socio.index') }}" class="grid grid-cols-1 sm:grid-cols-5 gap-3 items-end">
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1">Buscar Socio</label>
                <input 
                    type="text" 
                    name="buscar" 
                    value="{{ request('buscar') }}" 
                    placeholder="Paterno, Materno, Nombres, CI o Boleta..."
                    class="block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 sm:text-xs py-2 px-3 focus:border-blue-600 focus:ring-blue-600"
                >
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1">Grado</label>
                <select name="grado" class="block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 sm:text-xs py-2 px-3 focus:border-blue-600 focus:ring-blue-600">
                    <option value="">Todos los Grados</option>
                    @foreach(['SBTTE.', 'TTE.', 'CAP.', 'MY.', 'TCNL.', 'CNL.', 'GRAL.'] as $g)
                        <option value="{{ $g }}" @selected(request('grado') == $g)>{{ $g }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1">Situación</label>
                <select name="situacion" class="block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 sm:text-xs py-2 px-3 focus:border-blue-600 focus:ring-blue-600">
                    <option value="">Todas</option>
                    @foreach(['ACTIVO', 'RESERVA ACTIVA', 'PASIVO', 'PASIVO(+)'] as $s)
                        <option value="{{ $s }}" @selected(request('situacion') == $s)>{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 px-3 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold transition-colors">
                    Filtrar
                </button>
                @if(request()->anyFilled(['buscar', 'grado', 'situacion', 'estado']))
                    <a href="{{ route('admin.socio.index') }}" class="py-2 px-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition-colors">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabla de Socios -->
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-slate-800/50 text-[11px] uppercase tracking-wider font-bold text-slate-500 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-6 py-3.5">Oficial / Nombre</th>
                        <th class="px-6 py-3.5">Grado & Arma</th>
                        <th class="px-6 py-3.5">CI & Boleta</th>
                        <th class="px-6 py-3.5">Situación</th>
                        <th class="px-6 py-3.5">Estado</th>
                        <th class="px-6 py-3.5 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($socios as $socio)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($socio->foto)
                                        <img src="{{ asset('storage/' . $socio->foto) }}" class="h-10 w-10 rounded-xl object-cover border border-slate-200">
                                    @else
                                        <div class="h-10 w-10 rounded-xl bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-sm">
                                            {{ strtoupper(substr($socio->nombres, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <span class="font-bold text-slate-900 dark:text-white block">{{ $socio->paterno }} {{ $socio->materno }} {{ $socio->nombres }}</span>
                                        <span class="text-xs text-slate-400">ID Titular: #{{ $socio->id_titular }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-800 dark:text-slate-200 block">{{ $socio->grado }}</span>
                                <span class="text-xs text-slate-500">{{ $socio->arma_especialidad }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <span class="font-mono font-bold text-slate-700 dark:text-slate-300 block">CI: {{ $socio->ci }} {{ $socio->expedido }}</span>
                                <span class="text-slate-500">Boleta: {{ $socio->boleta_pago ?? 'S/B' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 text-[11px] font-bold rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 uppercase">
                                    {{ $socio->situacion_actual }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-[11px] font-bold rounded-full uppercase {{ strtolower($socio->estado_socio) === 'activo' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-rose-100 text-rose-800 border border-rose-300' }}">
                                    {{ $socio->estado_socio }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.socio.ficha', $socio->id_titular) }}" 
                                       class="p-1.5 text-slate-500 hover:text-blue-600 rounded-lg hover:bg-slate-100 transition-colors" title="Ver Ficha">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    <a href="{{ route('admin.socio.beneficiario.index', $socio->id_titular) }}" 
                                       class="p-1.5 text-slate-500 hover:text-emerald-600 rounded-lg hover:bg-slate-100 transition-colors" title="Ver Beneficiarios">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </a>

                                    <a href="{{ route('admin.socio.edit', $socio->id_titular) }}" 
                                       class="p-1.5 text-slate-500 hover:text-amber-600 rounded-lg hover:bg-slate-100 transition-colors" title="Editar Socio">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <a href="{{ route('credenciales.create', ['tipo' => 'titular', 'id' => $socio->id_titular]) }}" 
                                       class="p-1.5 text-slate-500 hover:text-purple-600 rounded-lg hover:bg-slate-100 transition-colors" title="Generar Credencial">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                                No se encontraron socios con los filtros indicados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($socios->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                {{ $socios->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
