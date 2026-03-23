<div class="flex flex-1 flex-col gap-4 rounded-xl">
    <div class="rounded-xl border border-zinc-200 bg-white p-4 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <div class="flex items-center justify-between gap-2">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">
                {{ __('Big Rocks for My Division') }}
            </h2>
            <button
                type="button"
                wire:click="createNew"
                class="inline-flex items-center rounded-full bg-zinc-900 px-3 py-1.5 text-[11px] font-medium text-white hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200"
            >
                {{ __('New Big Rock') }}
            </button>
        </div>

        <div class="mt-3 grid gap-3 md:grid-cols-2">
            @forelse ($bigRocks as $bigRock)
                <div class="flex flex-col justify-between rounded-xl border border-zinc-200 bg-zinc-50 p-3 text-xs dark:border-zinc-700 dark:bg-zinc-800">
                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <h3 class="text-xs font-semibold text-zinc-900 dark:text-zinc-50">
                                {{ $bigRock->title }}
                            </h3>
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-medium
                                {{ $bigRock->status->value === 'active' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-100' : 'bg-zinc-200 text-zinc-700 dark:bg-zinc-700 dark:text-zinc-100' }}">
                                {{ ucfirst($bigRock->status->value) }}
                            </span>
                        </div>
                        @if ($bigRock->description)
                            <p class="mt-1 text-[11px] text-zinc-700 dark:text-zinc-200">
                                {{ $bigRock->description }}
                            </p>
                        @endif
                        @if ($bigRock->period_start || $bigRock->period_end)
                            <p class="mt-1 text-[11px] text-zinc-500 dark:text-zinc-400">
                                {{ __('Period:') }}
                                {{ optional($bigRock->period_start)->format('d M Y') ?? '—' }}
                                –
                                {{ optional($bigRock->period_end)->format('d M Y') ?? '—' }}
                            </p>
                        @endif
                    </div>
                    <div class="mt-3 flex justify-end gap-2">
                        <button
                            type="button"
                            wire:click="edit({{ $bigRock->id }})"
                            class="inline-flex items-center rounded-full border border-zinc-300 bg-white px-3 py-1 text-[11px] font-medium text-zinc-800 hover:bg-zinc-100 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:bg-zinc-800"
                        >
                            {{ __('Edit') }}
                        </button>
                        @if ($bigRock->status->value === 'active')
                            <button
                                type="button"
                                wire:click="archive({{ $bigRock->id }})"
                                class="inline-flex items-center rounded-full border border-red-200 bg-red-50 px-3 py-1 text-[11px] font-medium text-red-700 hover:bg-red-100 dark:border-red-700 dark:bg-red-900/40 dark:text-red-100"
                            >
                                {{ __('Archive') }}
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-xs text-zinc-500 dark:text-zinc-400">
                    {{ __('No Big Rocks have been defined yet for this division.') }}
                </p>
            @endforelse
        </div>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">
            {{ $editingId ? __('Edit Big Rock') : __('Create Big Rock') }}
        </h2>

        <div class="mt-3 grid gap-3 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                    {{ __('Title') }}
                </label>
                <input
                    type="text"
                    wire:model.defer="title"
                    class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                >
                @error('title')<p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                    {{ __('Description (optional)') }}
                </label>
                <textarea
                    wire:model.defer="description"
                    rows="3"
                    class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                ></textarea>
                @error('description')<p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                    {{ __('Status') }}
                </label>
                <select
                    wire:model.defer="status"
                    class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                >
                    <option value="active">{{ __('Active') }}</option>
                    <option value="archived">{{ __('Archived') }}</option>
                </select>
                @error('status')<p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                    {{ __('Period Start (optional)') }}
                </label>
                <input
                    type="date"
                    wire:model.defer="period_start"
                    class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                >
                @error('period_start')<p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                    {{ __('Period End (optional)') }}
                </label>
                <input
                    type="date"
                    wire:model.defer="period_end"
                    class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                >
                @error('period_end')<p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-4 flex justify-end gap-2">
            <button
                type="button"
                wire:click="save"
                class="inline-flex items-center rounded-full bg-zinc-900 px-4 py-1.5 text-xs font-medium text-white hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200"
            >
                {{ __('Save Big Rock') }}
            </button>
        </div>
    </div>
</div>
