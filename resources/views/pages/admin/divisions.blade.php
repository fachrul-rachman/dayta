<div class="flex flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">
                {{ __('Division Directory') }}
            </h2>
            <button
                type="button"
                wire:click="create"
                class="inline-flex items-center rounded-full bg-zinc-900 px-3 py-1.5 text-xs font-medium text-white hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200"
            >
                {{ __('Create Division') }}
            </button>
        </div>

        <div class="grid gap-4 md:grid-cols-[2fr,3fr]">
            <div class="rounded-xl border border-zinc-200 bg-white p-4 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h3 class="text-xs font-semibold text-zinc-800 dark:text-zinc-100">
                    {{ $editingId ? __('Edit Division') : __('New Division') }}
                </h3>
                <div class="mt-3 space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                            {{ __('Name') }}
                        </label>
                        <input type="text" wire:model.defer="name" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50">
                        @error('name')<p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" wire:model.defer="is_active" id="division_is_active" class="h-3 w-3 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-500 dark:border-zinc-600 dark:text-zinc-100">
                        <label for="division_is_active" class="text-xs text-zinc-700 dark:text-zinc-200">
                            {{ __('Active') }}
                        </label>
                    </div>
                </div>
                <div class="mt-4 flex justify-end gap-2">
                    <button
                        type="button"
                        wire:click="save"
                        class="inline-flex items-center rounded-full bg-zinc-900 px-4 py-1.5 text-xs font-medium text-white hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200"
                    >
                        {{ __('Save') }}
                    </button>
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-4 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h3 class="text-xs font-semibold text-zinc-800 dark:text-zinc-100">
                    {{ __('Divisions') }}
                </h3>
                <div class="mt-3 grid gap-2 md:grid-cols-2">
                    @forelse ($divisions as $division)
                        <div class="flex flex-col justify-between rounded-xl border border-zinc-200 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800">
                            <div>
                                <div class="text-xs font-semibold text-zinc-900 dark:text-zinc-50">
                                    {{ $division->name }}
                                </div>
                                <div class="mt-1 text-[11px] text-zinc-500 dark:text-zinc-400">
                                    {{ $division->is_active ? __('Active') : __('Inactive') }}
                                </div>
                            </div>
                            <div class="mt-2 flex justify-end">
                                <button
                                    type="button"
                                    wire:click="edit({{ $division->id }})"
                                    class="inline-flex items-center rounded-full border border-zinc-300 px-3 py-0.5 text-[11px] font-medium text-zinc-700 hover:bg-zinc-100 dark:border-zinc-600 dark:text-zinc-100 dark:hover:bg-zinc-800"
                                >
                                    {{ __('Edit') }}
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-zinc-500 dark:text-zinc-400">
                            {{ __('No divisions defined yet.') }}
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
