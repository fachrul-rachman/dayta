<div class="flex flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-zinc-200 bg-white p-4 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">
                {{ __('Override Details') }}
            </h2>
            <div class="mt-3 grid grid-cols-1 gap-3 md:grid-cols-4">
                <div>
                    <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                        {{ __('From') }}
                    </label>
                    <input type="date" wire:model.defer="date_from" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                        {{ __('To') }}
                    </label>
                    <input type="date" wire:model.defer="date_to" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                        {{ __('Division') }}
                    </label>
                    <select wire:model.defer="division_id" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50">
                        <option value="">{{ __('All') }}</option>
                        @foreach ($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                        {{ __('User') }}
                    </label>
                    <select wire:model.defer="user_id" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50">
                        <option value="">{{ __('All') }}</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-[3fr,2fr]">
            <div class="rounded-xl border border-zinc-200 bg-white p-4 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h3 class="text-xs font-semibold text-zinc-800 dark:text-zinc-100">
                    {{ __('Override Targets') }}
                </h3>
                <div class="mt-3 space-y-2">
                    @forelse ($entries as $entry)
                        <button
                            type="button"
                            wire:click="selectTarget({{ $entry->id }})"
                            class="w-full rounded-xl border {{ $target_entry_id === $entry->id ? 'border-zinc-900 dark:border-zinc-100' : 'border-zinc-200 dark:border-zinc-700' }} bg-zinc-50 p-3 text-left hover:bg-zinc-100 dark:bg-zinc-800 dark:hover:bg-zinc-700"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-xs font-semibold text-zinc-900 dark:text-zinc-50">
                                        {{ $entry->user->name }}
                                    </div>
                                    <div class="mt-0.5 text-[11px] text-zinc-500 dark:text-zinc-400">
                                        {{ $entry->entry_date->toFormattedDateString() }} · {{ $entry->division?->name ?? __('No division') }}
                                    </div>
                                </div>
                                <div class="text-right text-[11px] text-zinc-500 dark:text-zinc-400">
                                    {{ __('Plan:') }} {{ $entry->plan_status->name }}<br>
                                    {{ __('Realization:') }} {{ $entry->realization_status->name }}
                                </div>
                            </div>
                        </button>
                    @empty
                        <p class="text-zinc-500 dark:text-zinc-400">
                            {{ __('No entries found for the selected filters.') }}
                        </p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-4 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h3 class="text-xs font-semibold text-zinc-800 dark:text-zinc-100">
                    {{ __('Submit Override') }}
                </h3>
                @if (! $target_entry_id)
                    <p class="mt-3 text-zinc-500 dark:text-zinc-400">
                        {{ __('Select a target entry to override its status.') }}
                    </p>
                @else
                    <div class="mt-3 space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                                {{ __('Target Item') }}
                            </label>
                            @php
                                $selected = $entries->firstWhere('id', $target_entry_id);
                            @endphp
                            @if ($selected)
                                <select wire:model="target_item_id" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50">
                                    <option value="">{{ __('Select item') }}</option>
                                    @foreach ($selected->items as $item)
                                        <option value="{{ $item->id }}">
                                            {{ Str::limit($item->description, 60) }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" disabled class="mt-1 w-full rounded-lg border border-dashed border-zinc-300 bg-zinc-50 px-2 py-1 text-xs text-zinc-400 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-600" value="{{ __('Select an entry first') }}">
                            @endif
                            @error('target_item_id')<p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                                {{ __('New Text') }}
                            </label>
                            <textarea wire:model.defer="new_text" rows="3" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"></textarea>
                            @error('new_text')<p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                                {{ __('Reason') }}
                            </label>
                            <textarea wire:model.defer="reason" rows="3" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"></textarea>
                            @error('reason')<p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button
                            type="button"
                            wire:click="submitOverride"
                            class="inline-flex items-center rounded-full bg-zinc-900 px-4 py-1.5 text-xs font-medium text-white hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200"
                        >
                            {{ __('Submit Override') }}
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
