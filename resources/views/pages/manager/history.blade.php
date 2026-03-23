<div class="flex flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">
                {{ __('Reporting History') }}
            </h2>
            <div class="mt-3 grid grid-cols-1 gap-3 text-xs md:grid-cols-4">
                <div>
                    <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                        {{ __('From') }}
                    </label>
                    <input
                        type="date"
                        wire:model.defer="from"
                        class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                    />
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                        {{ __('To') }}
                    </label>
                    <input
                        type="date"
                        wire:model.defer="to"
                        class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                    />
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-4 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            @if ($entries->isEmpty())
                <p class="text-zinc-500 dark:text-zinc-400">
                    {{ __('No history available for the selected period.') }}
                </p>
            @else
                <div class="space-y-4">
                    @foreach ($entries as $entry)
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800">
                            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-dashed border-zinc-200 pb-2 text-xs dark:border-zinc-700">
                                <div>
                                    <div class="font-medium text-zinc-900 dark:text-zinc-50">
                                        {{ $entry->entry_date->toFormattedDateString() }}
                                    </div>
                                    <div class="mt-0.5 text-[11px] text-zinc-500 dark:text-zinc-400">
                                        {{ __('Plan:') }} {{ $entry->plan_status->name }} · {{ __('Realization:') }} {{ $entry->realization_status->name }}
                                    </div>
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
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
