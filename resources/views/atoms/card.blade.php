@props([
    'padding' => 'p-6',
])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm transition-all ' . $padding]) }}>
    {{ $slot }}
</div>
