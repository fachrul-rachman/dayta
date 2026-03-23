@props(['title', 'value' => null, 'helper' => null, 'href' => null])

@php
    $classes = 'group flex flex-col justify-between rounded-xl border border-zinc-200 bg-white p-4 text-left shadow-sm transition hover:-translate-y-0.5 hover:border-zinc-300 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900';
@endphp

@if ($href)
    <a href="{{ $href }}" wire:navigate class="{{ $classes }} cursor-pointer">
        <div class="flex items-center justify-between gap-2">
            <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400 group-hover:text-zinc-700 dark:group-hover:text-zinc-300">
                {{ __($title) }}
            </div>
            <span class="text-[11px] text-zinc-400 group-hover:text-zinc-500 dark:text-zinc-500 dark:group-hover:text-zinc-300">
                →
            </span>
        </div>
        <div class="mt-2 text-2xl font-semibold text-zinc-900 dark:text-zinc-50">
            {{ $value ?? '—' }}
        </div>
        <div class="mt-2 text-xs leading-snug text-zinc-500 dark:text-zinc-400">
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
        <div class="mt-2 text-xs leading-snug text-zinc-500 dark:text-zinc-400">
            {{ $helper }}
        </div>
    </div>
@endif

