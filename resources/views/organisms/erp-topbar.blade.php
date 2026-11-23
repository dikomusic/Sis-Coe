@props([
    'title' => 'SISCOE ERP',
    'breadcrumbs' => [],
])

<header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30">
    <div class="flex items-center gap-2.5 sm:gap-3">
        <!-- Botón Hamburguesa para Móvil y Multipantallas (Atomic Design: Atom) -->
        <button 
            type="button" 
            @click="sidebarOpen = true" 
            class="lg:hidden p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-[#FF0066]"
            aria-label="Abrir Menú de Navegación"
        >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <h1 class="text-base sm:text-lg font-black text-slate-800 dark:text-white tracking-tight truncate">
            {{ $title }}
        </h1>
    </div>

    <div class="flex items-center gap-4">
        <!-- Indicador de Fecha Institucional -->
        <div class="hidden sm:flex flex-col text-right">
            <span class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">
                {{ now()->locale('es')->isoFormat('dddd D [de] MMMM, YYYY') }}
            </span>
            <span class="text-[10px] text-[#EDD00A] font-bold">Círculo de Oficiales del Ejército</span>
        </div>

        <div class="h-8 w-px bg-slate-200 dark:bg-slate-800 hidden sm:block"></div>

        <div class="flex items-center gap-2">
            <span class="px-2.5 py-1 text-[11px] font-bold rounded-full bg-[#FF0066]/10 text-[#FF0066] border border-[#FF0066]/30 uppercase">
                {{ auth()->user()->rol ?? 'INVITADO' }}
            </span>
        </div>
    </div>
</header>
