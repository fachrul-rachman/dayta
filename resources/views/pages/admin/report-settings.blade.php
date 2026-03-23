<div class="flex flex-1 flex-col gap-4 rounded-xl">
    <div class="rounded-xl border border-zinc-200 bg-white p-4 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">
            {{ __('Current Settings') }}
        </h2>
        <div class="mt-3 grid grid-cols-1 gap-3 md:grid-cols-2">
            <div>
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                    {{ __('Plan Open Time') }}
                </label>
                <input type="time" wire:model.defer="plan_open_rule" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50">
                @error('plan_open_rule')<p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                    {{ __('Plan Close Time') }}
                </label>
                <input type="time" wire:model.defer="plan_close_rule" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50">
                @error('plan_close_rule')<p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                    {{ __('Realization Open Time') }}
                </label>
                <input type="time" wire:model.defer="realization_open_rule" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50">
                @error('realization_open_rule')<p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                    {{ __('Realization Close Time') }}
                </label>
                <input type="time" wire:model.defer="realization_close_rule" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50">
                @error('realization_close_rule')<p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="mt-4 flex items-center justify-between gap-2">
            @if ($saved)
                <p class="text-[11px] text-emerald-700 dark:text-emerald-300">
                    {{ __('Settings have been saved.') }}
                </p>
            @else
                <span class="text-[11px] text-zinc-400 dark:text-zinc-500">
                    {{ __('Configure daily reporting windows in Jakarta time.') }}
                </span>
            @endif
            <button
                type="button"
                wire:click="save"
                class="inline-flex items-center rounded-full bg-zinc-900 px-4 py-1.5 text-xs font-medium text-white hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200"
            >
                {{ __('Save Settings') }}
            </button>
        </div>
    </div>
</div>
