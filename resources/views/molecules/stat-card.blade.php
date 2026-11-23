@props([
    'title' => '',
    'value' => '0',
    'subtitle' => null,
    'icon' => 'currency', // currency, users, calendar, check
    'trend' => null,
    'color' => 'blue', // blue, emerald, amber, purple
])

@php
    $colorClasses = [
        'coe'     => 'bg-[#FF0066]/10 text-[#FF0066] border-[#FF0066]/30',
        'pink'    => 'bg-[#FF0066]/10 text-[#FF0066] border-[#FF0066]/30',
        'gold'    => 'bg-[#EDD00A]/15 text-[#CAAC08] dark:text-[#EDD00A] border-[#EDD00A]/40',
        'blue'    => 'bg-[#FF0066]/10 text-[#FF0066] border-[#FF0066]/30',
        'emerald' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400 border-emerald-100 dark:border-emerald-900',
        'amber'   => 'bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400 border-amber-100 dark:border-amber-900',
        'purple'  => 'bg-purple-50 text-purple-600 dark:bg-purple-950/50 dark:text-purple-400 border-purple-100 dark:border-purple-900',
    ];
@endphp

<div class="relative overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm transition-all hover:shadow-md">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ $title }}</p>
            <p class="mt-2 text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $value }}</p>
            @if($subtitle)
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $subtitle }}</p>
            @endif
        </div>

        <div class="rounded-xl p-3 border {{ $colorClasses[$color] ?? $colorClasses['blue'] }}">
            @if($icon === 'currency')
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @elseif($icon === 'users')
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            @elseif($icon === 'calendar')
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            @else
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @endif
        </div>
    </div>
</div>
