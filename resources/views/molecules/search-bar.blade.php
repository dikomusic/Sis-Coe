@props([
    'action' => '',
    'placeholder' => 'Buscar...',
    'value' => '',
    'resetUrl' => null,
])

<form method="GET" action="{{ $action }}" class="w-full flex flex-col sm:flex-row items-center gap-3">
    <div class="relative flex-1 w-full">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input 
            type="text" 
            name="buscar" 
            value="{{ $value }}" 
            placeholder="{{ $placeholder }}"
            class="block w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 py-2.5 pl-10 pr-4 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 shadow-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20"
        >
    </div>

    {{ $slot }}

    <div class="flex items-center gap-2 w-full sm:w-auto">
        <button type="submit" class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/20 transition-all">
            Buscar
        </button>
        @if($resetUrl && !empty($value))
            <a href="{{ $resetUrl }}" class="px-3 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition-colors">
                Limpiar
            </a>
        @endif
    </div>
</form>
