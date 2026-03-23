<div class="flex flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-zinc-200 bg-white p-4 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">
                {{ __('Team Submission') }}
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
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-4 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="mt-1 space-y-2">
                @forelse ($entries as $entry)
                    <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-semibold text-zinc-900 dark:text-zinc-50">
                                    {{ $entry->user->name }}
                                </div>
                                <div class="mt-0.5 text-[11px] text-zinc-500 dark:text-zinc-400">
                                    {{ $entry->entry_date->toFormattedDateString() }}
                                </div>
                            </div>
                            <div class="text-right text-[11px] text-zinc-500 dark:text-zinc-400">
                                {{ __('Plan:') }} {{ $entry->plan_status->name }}<br>
                                {{ __('Realization:') }} {{ $entry->realization_status->name }}
                            </div>
                        </div>
                        <div class="mt-3 grid grid-cols-1 gap-3 md:grid-cols-2">
                            <div>
                                <div class="text-[11px] font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                                    {{ __('Plan') }}
                                </div>
                                <ul class="mt-1 space-y-1">
                                    @foreach ($entry->items->whereNotNull('planned_hours') as $item)
                                        <li class="rounded-lg bg-white px-2 py-1 text-[11px] text-zinc-700 shadow-sm dark:bg-zinc-900 dark:text-zinc-100">
                                            <div>{{ $item->description }}</div>
                                            <div class="mt-0.5 text-[10px] text-zinc-500 dark:text-zinc-400">
                                                {{ __('Type:') }} {{ ucfirst(str_replace('_', ' ', $item->work_type->value)) }}
                                                @if ($item->planned_hours)
                                                    · {{ __('Planned:') }} {{ $item->planned_hours }}
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div>
                                <div class="text-[11px] font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                                    {{ __('Realization') }}
                                </div>
                                <ul class="mt-1 space-y-1">
                                    @foreach ($entry->items->whereNotNull('realized_hours') as $item)
                                        <li class="rounded-lg bg-white px-2 py-1 text-[11px] text-zinc-700 shadow-sm dark:bg-zinc-900 dark:text-zinc-100">
                                            <div>{{ $item->description }}</div>
                                            <div class="mt-0.5 text-[10px] text-zinc-500 dark:text-zinc-400">
                                                {{ __('Type:') }} {{ ucfirst(str_replace('_', ' ', $item->work_type->value)) }}
                                                @if ($item->realized_hours)
                                                    · {{ __('Realized:') }} {{ $item->realized_hours }}
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-zinc-500 dark:text-zinc-400">
                        {{ __('No entries found for your division.') }}
                    </p>
                @endforelse
            </div>
        </div>
    </div>
</div>
