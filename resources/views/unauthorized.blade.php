<x-layouts::app :title="__('Unauthorized')">
    <div class="flex h-full w-full flex-1 flex-col items-center justify-center gap-4 rounded-xl">
        <div class="max-w-md text-center">
            <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-50">
                {{ __('You are not authorized to view this page.') }}
            </h1>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                {{ __('Your role does not have access to this area. Please return to your dashboard.') }}
            </p>
        </div>
        <div>
            <a
                href="{{ route('home') }}"
                class="inline-flex items-center rounded-full bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200"
            >
                {{ __('Back to home') }}
            </a>
        </div>
    </div>
</x-layouts::app>

