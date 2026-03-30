@props(['title', 'value' => null, 'helper' => null, 'href' => null, 'variant' => 'default'])

@php
    $variantClasses = match ($variant) {
        'success' => 'border-emerald-200 dark:border-emerald-800',
        'warning' => 'border-amber-200 dark:border-amber-800',
        'danger'  => 'border-red-200 dark:border-red-800',
        default   => 'border-zinc-200 dark:border-zinc-700',
    };

    $valueClasses = match ($variant) {
        'success' => 'text-emerald-700 dark:text-emerald-400',
        'warning' => 'text-amber-700 dark:text-amber-400',
        'danger'  => 'text-red-700 dark:text-red-400',
        default   => 'text-zinc-900 dark:text-zinc-50',
    };

    $classes = "group flex flex-col justify-between rounded-xl border bg-white p-4 text-left shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:bg-zinc-900 {$variantClasses}";
@endphp

@if ($href)
    <a href="{{ $href }}" wire:navigate class="{{ $classes }} cursor-pointer">
        <div class="flex items-center justify-between gap-2">
            <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400 group-hover:text-zinc-700 dark:group-hover:text-zinc-300">
                {{ __($title) }}
            </div>
            <span class="text-xs text-zinc-400 group-hover:text-zinc-500 dark:text-zinc-500 dark:group-hover:text-zinc-300">
                →
            </span>
        </div>
        <div class="mt-2 text-2xl font-semibold {{ $valueClasses }}">
            {{ $value ?? '—' }}
        </div>
        @if ($helper)
            <div class="mt-2 text-xs leading-snug text-zinc-500 dark:text-zinc-400">
                {{ $helper }}
            </div>
        @endif
    </a>
@else
    <div class="{{ $classes }}">
        <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
            {{ __($title) }}
        </div>
        <div class="mt-2 text-2xl font-semibold {{ $valueClasses }}">
            {{ $value ?? '—' }}
        </div>
        @if ($helper)
            <div class="mt-2 text-xs leading-snug text-zinc-500 dark:text-zinc-400">
                {{ $helper }}
            </div>
        @endif
    </div>
@endif
