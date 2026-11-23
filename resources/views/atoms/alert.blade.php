@props([
    'type' => 'success', // success, error, warning, info
    'title' => null,
])

@php
    $types = [
        'success' => [
            'bg' => 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200',
            'icon' => '<svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        ],
        'error' => [
            'bg' => 'bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200',
            'icon' => '<svg class="h-5 w-5 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        ],
        'warning' => [
            'bg' => 'bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-200',
            'icon' => '<svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>',
        ],
        'info' => [
            'bg' => 'bg-sky-50 dark:bg-sky-950/40 border-sky-200 dark:border-sky-800 text-sky-800 dark:text-sky-200',
            'icon' => '<svg class="h-5 w-5 text-sky-600 dark:text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        ],
    ];

    $config = $types[$type] ?? $types['info'];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-xl border p-4 flex items-start gap-3 text-sm ' . $config['bg']]) }}>
    <div class="flex-shrink-0 mt-0.5">
        {!! $config['icon'] !!}
    </div>
    <div class="flex-1">
        @if($title)
            <h5 class="font-bold text-sm mb-0.5">{{ $title }}</h5>
        @endif
        <div class="text-xs leading-relaxed">
            {{ $slot }}
        </div>
    </div>
</div>
