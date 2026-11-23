@props([
    'variant' => 'primary', // primary, secondary, success, danger, outline, ghost
    'size' => 'md', // sm, md, lg
    'type' => 'button',
    'disabled' => false,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-bold rounded-xl shadow-sm transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs gap-1.5',
        'md' => 'px-4 py-2 text-sm gap-2',
        'lg' => 'px-5 py-2.5 text-base gap-2.5',
    ];

    $variants = [
        'primary'   => 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500 shadow-blue-600/25 active:scale-[0.98]',
        'secondary' => 'bg-slate-700 hover:bg-slate-800 text-white focus:ring-slate-600 shadow-slate-700/20 active:scale-[0.98]',
        'success'   => 'bg-emerald-600 hover:bg-emerald-700 text-white focus:ring-emerald-500 shadow-emerald-600/20 active:scale-[0.98]',
        'danger'    => 'bg-rose-600 hover:bg-rose-700 text-white focus:ring-rose-500 shadow-rose-600/20 active:scale-[0.98]',
        'outline'   => 'border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 focus:ring-slate-400 active:scale-[0.98]',
        'ghost'     => 'hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 focus:ring-slate-400 shadow-none',
    ];

    $classes = $baseClasses . ' ' . ($sizes[$size] ?? $sizes['md']) . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }} @if($disabled) disabled @endif>
    {{ $slot }}
</button>
