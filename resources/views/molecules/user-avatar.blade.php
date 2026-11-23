@props([
    'name' => 'Usuario',
    'subtitle' => null,
    'size' => 'md', // sm, md, lg
])

@php
    $sizes = [
        'sm' => 'h-8 w-8 text-xs',
        'md' => 'h-10 w-10 text-sm',
        'lg' => 'h-12 w-12 text-base',
    ];
    $initial = strtoupper(substr($name, 0, 1));
@endphp

<div class="flex items-center gap-3">
    <div class="rounded-xl bg-gradient-to-tr from-blue-700 to-indigo-600 text-white font-extrabold flex items-center justify-center shadow-sm {{ $sizes[$size] ?? $sizes['md'] }}">
        {{ $initial }}
    </div>
    @if($subtitle !== false)
        <div class="text-left">
            <p class="text-sm font-bold text-slate-800 dark:text-white leading-tight truncate">{{ $name }}</p>
            @if($subtitle)
                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ $subtitle }}</p>
            @endif
        </div>
    @endif
</div>
