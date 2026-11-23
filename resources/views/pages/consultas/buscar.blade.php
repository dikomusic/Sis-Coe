@extends('templates.erp-layout', ['title' => 'Resultados de Consulta'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Resultados de Búsqueda</h2>
            <p class="text-xs text-slate-500 mt-0.5">Mostrando registros coincidentes con: <strong>"{{ $q }}"</strong> en <strong>{{ $tipo }}</strong></p>
        </div>

        <a href="{{ route('consultas.index') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
            ← Volver a Búsqueda
        </a>
    </div>

    <!-- Barra de búsqueda rápida -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <form method="GET" action="{{ route('consultas.search') }}" class="flex flex-col sm:flex-row gap-3">
            <input type="hidden" name="mode" value="{{ $mode }}">
            <div class="flex-1">
                <input 
                    type="text" 
                    name="q" 
                    value="{{ $q }}" 
                    placeholder="Buscar por Nombre, Apellidos, CI o Boleta..." 
                    class="block w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:border-blue-600"
                >
            </div>
            <div class="w-full sm:w-44">
                <select name="tipo" class="block w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 focus:border-blue-600">
                    <option value="SOCIO" @selected($tipo === 'SOCIO')>Socio Titular</option>
                    <option value="BENEFICIARIO" @selected($tipo === 'BENEFICIARIO')>Beneficiario</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition-all">
                Buscar
            </button>
        </form>
    </div>

    <!-- Tabla de Resultados -->
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-slate-800/50 text-[11px] uppercase tracking-wider font-bold text-slate-500 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-6 py-3.5">Nombre / Oficial</th>
                        <th class="px-6 py-3.5">CI</th>
                        <th class="px-6 py-3.5">Tipo / Grado</th>
                        <th class="px-6 py-3.5 text-right">Ver Ficha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @if($tipo === 'SOCIO')
                        @forelse($socios as $socio)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                    {{ $socio->paterno }} {{ $socio->materno }} {{ $socio->nombres }}
                                </td>
                                <td class="px-6 py-4 text-xs font-mono font-bold text-slate-600 dark:text-slate-300">
                                    {{ $socio->ci }} {{ $socio->expedido }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                        {{ $socio->grado }} - {{ $socio->arma_especialidad }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('consultas.detalle', ['id' => $socio->id_titular, 'tipo' => 'socio']) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                                        Consultar Detalle →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-400">
                                    No se encontraron socios con el término ingresado.
                                </td>
                            </tr>
                        @endforelse
                    @else
                        @forelse($beneficiarios as $ben)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                    {{ $ben->paterno }} {{ $ben->materno }} {{ $ben->nombres }}
                                </td>
                                <td class="px-6 py-4 text-xs font-mono font-bold text-slate-600 dark:text-slate-300">
                                    {{ $ben->ci }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        {{ $ben->parentesco }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('consultas.detalle', ['id' => $ben->id_beneficiario, 'tipo' => 'beneficiario']) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                                        Consultar Detalle →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-400">
                                    No se encontraron beneficiarios con el término ingresado.
                                </td>
                            </tr>
                        @endforelse
                    @endif
                </tbody>
            </table>
        </div>

        @php
            $paginador = $tipo === 'SOCIO' ? $socios : $beneficiarios;
        @endphp
        @if(method_exists($paginador, 'hasPages') && $paginador->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                {{ $paginador->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
