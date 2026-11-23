@extends('templates.erp-layout', ['title' => 'Gestión de Beneficiarios'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            @if(isset($titular))
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Titular: {{ $titular->grado }} {{ $titular->paterno }} {{ $titular->nombres }}</span>
                <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Beneficiarios del Socio</h2>
            @else
                <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Padrón General de Beneficiarios</h2>
                <p class="text-xs text-slate-500 mt-0.5">Familiares con derecho a carnet y acceso a instalaciones del COE.</p>
            @endif
        </div>

        <div class="flex items-center gap-2">
            @if(isset($titular))
                <a href="{{ route('admin.socio.index') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
                    ← Volver a Socios
                </a>
                <a href="{{ route('admin.socio.beneficiario.create', $titular->id_titular) }}" 
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/25 transition-all">
                    + Registrar Beneficiario
                </a>
            @endif
        </div>
    </div>

    <!-- Tabla de Beneficiarios -->
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-slate-800/50 text-[11px] uppercase tracking-wider font-bold text-slate-500 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-6 py-3.5">Beneficiario / Nombre</th>
                        <th class="px-6 py-3.5">Parentesco</th>
                        <th class="px-6 py-3.5">CI</th>
                        @if(!isset($titular))
                            <th class="px-6 py-3.5">Socio Titular</th>
                        @endif
                        <th class="px-6 py-3.5">Estado</th>
                        <th class="px-6 py-3.5 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($beneficiarios as $ben)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($ben->foto)
                                        <img src="{{ asset('storage/' . $ben->foto) }}" class="h-10 w-10 rounded-xl object-cover border border-slate-200">
                                    @else
                                        <div class="h-10 w-10 rounded-xl bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-sm">
                                            {{ strtoupper(substr($ben->nombres, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <span class="font-bold text-slate-900 dark:text-white block">{{ $ben->paterno }} {{ $ben->materno }} {{ $ben->nombres }}</span>
                                        <span class="text-xs text-slate-400">ID: #{{ $ben->id_beneficiario }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800 border border-blue-200 uppercase">
                                    {{ $ben->parentesco }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs font-mono font-bold text-slate-700 dark:text-slate-300">
                                {{ $ben->ci }} {{ $ben->expedido }}
                            </td>
                            @if(!isset($titular))
                                <td class="px-6 py-4 text-xs">
                                    @if($ben->socioTitular)
                                        <span class="font-bold text-slate-800 dark:text-slate-200 block">{{ $ben->socioTitular->grado }} {{ $ben->socioTitular->paterno }} {{ $ben->socioTitular->nombres }}</span>
                                        <span class="text-slate-400">ID: #{{ $ben->socioTitular->id_titular }}</span>
                                    @else
                                        <span class="text-slate-400">Sin asignar</span>
                                    @endif
                                </td>
                            @endif
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 text-[11px] font-bold rounded-full bg-emerald-100 text-emerald-800">
                                    Activo
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    @php
                                        $idTitularParam = $ben->id_titular ?: 1;
                                    @endphp
                                    <a href="{{ route('admin.socio.beneficiario.edit', [$idTitularParam, $ben->id_beneficiario]) }}" 
                                       class="p-1.5 text-slate-500 hover:text-amber-600 rounded-lg hover:bg-slate-100 transition-colors" title="Editar">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <a href="{{ route('credenciales.create', ['tipo' => 'beneficiario', 'id' => $ben->id_beneficiario]) }}" 
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
                            <td colspan="{{ isset($titular) ? 5 : 6 }}" class="px-6 py-8 text-center text-slate-400">
                                No se encontraron beneficiarios registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($beneficiarios, 'hasPages') && $beneficiarios->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                {{ $beneficiarios->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
