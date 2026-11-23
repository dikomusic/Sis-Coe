@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'helper' => null,
    'error' => null,
])

<div class="w-full">
    @if($label)
        <label @if($name) for="{{ $name }}" @endif class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
            {{ $label }}
            @if($required) <span class="text-rose-500">*</span> @endif
        </label>
    @endif

    <div class="relative rounded-xl shadow-sm">
        <input 
            type="{{ $type }}" 
            @if($name) name="{{ $name }}" id="{{ $name }}" @endif
            value="{{ $value ?? old($name) }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            {{ $attributes->merge([
                'class' => 'block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-blue-600 focus:ring-blue-600 sm:text-sm py-2.5 px-3 transition-colors ' . ($error ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500' : '')
            ]) }}
        >
    </div>

    @if($helper && !$error)
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $helper }}</p>
    @endif

    @if($error)
        <p class="mt-1 text-xs text-rose-600 dark:text-rose-400 font-medium">{{ $error }}</p>
    @endif
</div>
