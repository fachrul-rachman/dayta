@props(['title', 'value' => null, 'helper' => null, 'href' => null])

@php
    $classes = 'flex flex-col justify-between rounded-xl border border-zinc-200 bg-white p-4 text-left shadow-sm transition hover:-translate-y-0.5 hover:border-zinc-300 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900';
@endphp

@if ($href)
    <a href="{{ $href }}" wire:navigate class="{{ $classes }}">
        <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
            {{ __($title) }}
        </div>
        <div class="mt-2 text-2xl font-semibold text-zinc-900 dark:text-zinc-50">
            {{ $value ?? '—' }}
        </div>
        <div class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">
            {{ $helper }}
        </div>
    </a>
@else
    <div class="{{ $classes }}">
        <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
            {{ __($title) }}
        </div>
        <div class="mt-2 text-2xl font-semibold text-zinc-900 dark:text-zinc-50">
            {{ $value ?? '—' }}
        </div>
        <div class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">
            {{ $helper }}
        </div>
    </div>
@endif
