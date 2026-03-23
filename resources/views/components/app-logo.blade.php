@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="Dayta" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-transparent text-accent-foreground overflow-hidden dark:bg-white">
            <img
                src="{{ asset('images/logo-lestari.png') }}"
                alt="Dayta"
                class="h-7 w-7 object-contain"
                loading="lazy"
                onerror="this.style.display='none';"
            >
            <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="Dayta" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-transparent text-accent-foreground overflow-hidden dark:bg-white">
            <img
                src="{{ asset('images/logo-lestari.png') }}"
                alt="Dayta"
                class="h-7 w-7 object-contain"
                loading="lazy"
                onerror="this.style.display='none';"
            >
            <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
        </x-slot>
    </flux:brand>
@endif
