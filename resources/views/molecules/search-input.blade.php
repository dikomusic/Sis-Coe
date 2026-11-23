@props([
    'placeholder' => 'Buscar por CI, Nombre o Apellidos...',
    'model' => 'busquedaPersona',
])

<div class="relative w-full">
    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
        <svg class="h-5 w-5 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </div>

    <input 
        type="text" 
        x-model="{{ $model }}"
        @input.debounce.300ms="buscar()"
        @keydown.escape="resultados = []"
        placeholder="{{ $placeholder }}"
        autocomplete="off"
        {{ $attributes->merge([
            'class' => 'block w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 py-3.5 pl-11 pr-10 text-slate-900 dark:text-slate-100 placeholder-slate-400 shadow-sm focus:border-[#FF0066] focus:ring-2 focus:ring-[#FF0066]/20 text-base transition-all'
        ]) }}
    >

    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
        <template x-if="cargando">
            @include('atoms.spinner', ['size' => 'sm', 'color' => 'blue'])
        </template>
        <template x-if="!cargando && {{ $model }}.length > 0">
            <button type="button" @click="{{ $model }} = ''; resultados = []" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </template>
    </div>
</div>
