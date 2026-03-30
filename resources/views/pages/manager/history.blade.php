<div class="flex flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <h2 class="text-base font-semibold text-zinc-900 dark:text-zinc-50">
                {{ __('Reporting History') }}
            </h2>
            <div class="mt-3 grid grid-cols-1 gap-3 md:grid-cols-4 md:items-end">
                <div>
                    <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                        {{ __('From') }}
                    </label>
                    <input
                        type="date"
                        wire:model.defer="from"
                        class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                    />
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                        {{ __('To') }}
                    </label>
                    <input
                        type="date"
                        wire:model.defer="to"
                        class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                    />
                </div>
                <div>
                    <button
                        type="button"
                        wire:click="$refresh"
                        wire:loading.attr="disabled"
                        wire:target="$refresh"
                        class="mt-1 inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-indigo-500 dark:hover:bg-indigo-400"
                    >
                        <span wire:loading.remove wire:target="$refresh">
                            {{ __('Filter') }}
                        </span>
                        <span wire:loading wire:target="$refresh">
                            {{ __('Loading...') }}
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            @if ($entries->isEmpty())
                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                    {{ __('No history available for the selected period.') }}
                </p>
            @else
                <div class="space-y-4">
                    @foreach ($entries as $entry)
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800">
                            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-dashed border-zinc-200 pb-2 dark:border-zinc-700">
                                <div>
                                    <div class="text-sm font-medium text-zinc-900 dark:text-zinc-50">
                                        {{ $entry->entry_date->toFormattedDateString() }}
                                    </div>
                                    <div class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">
                                        {{ __('Plan:') }} {{ $entry->plan_status->name }} Â· {{ __('Realization:') }} {{ $entry->realization_status->name }}
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 grid grid-cols-1 gap-3 md:grid-cols-2">
                                <div>
                                    <div class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                                        {{ __('Plan') }}
                                    </div>
                                    <ul class="mt-1 space-y-1">
                                        @foreach ($entry->items->whereNotNull('planned_hours') as $item)
                                            <li class="rounded-lg bg-white px-3 py-2 text-sm text-zinc-700 shadow-sm dark:bg-zinc-900 dark:text-zinc-100">
                                                <div>{{ $item->description }}</div>
                                                <div class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">
                                                    {{ __('Type:') }} {{ ucfirst(str_replace('_', ' ', $item->work_type->value)) }}
                                                    @if ($item->planned_hours)
                                                        Â· {{ __('Planned:') }} {{ $item->planned_hours }}h
                                                    @endif
                                                </div>
                                                @if ($item->attachments->isNotEmpty())
                                                    <div class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                                                        {{ __('Attachments:') }}
                                                        <span class="space-x-2">
                                                            @foreach ($item->attachments as $attachment)
                                                                <a
                                                                    href="{{ \Illuminate\Support\Facades\Storage::disk(config('reporting.attachments_disk'))->url($attachment->file_path) }}"
                                                                    target="_blank"
                                                                    class="text-indigo-600 hover:underline dark:text-indigo-400"
                                                                >
                                                                    {{ $attachment->file_name }}
                                                                </a>
                                                            @endforeach
                                                        </span>
                                                    </div>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div>
                                    <div class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                                        {{ __('Realization') }}
                                    </div>
                                    <ul class="mt-1 space-y-1">
                                        @foreach ($entry->items->whereNotNull('realized_hours') as $item)
                                            <li class="rounded-lg bg-white px-3 py-2 text-sm text-zinc-700 shadow-sm dark:bg-zinc-900 dark:text-zinc-100">
                                                <div>{{ $item->description }}</div>
                                                <div class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">
                                                    {{ __('Type:') }} {{ ucfirst(str_replace('_', ' ', $item->work_type->value)) }}
                                                    @if ($item->realized_hours)
                                                        Â· {{ __('Realized:') }} {{ $item->realized_hours }}h
                                                    @endif
                                                </div>
                                                @if ($item->attachments->isNotEmpty())
                                                    <div class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                                                        {{ __('Attachments:') }}
                                                        <span class="space-x-2">
                                                            @foreach ($item->attachments as $attachment)
                                                                <a
                                                                    href="{{ \Illuminate\Support\Facades\Storage::disk(config('reporting.attachments_disk'))->url($attachment->file_path) }}"
                                                                    target="_blank"
                                                                    class="text-indigo-600 hover:underline dark:text-indigo-400"
                                                                >
                                                                    {{ $attachment->file_name }}
                                                                </a>
                                                            @endforeach
                                                        </span>
                                                    </div>
                                                @endif
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
