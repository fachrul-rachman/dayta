<div class="flex flex-1 flex-col gap-4 rounded-xl">
    <div class="rounded-xl border border-zinc-200 bg-white p-4 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">
                    {{ __('Division Summary') }}
                </h2>
                @if ($divisionName)
                    <p class="mt-1 text-[11px] text-zinc-500 dark:text-zinc-400">
                        {{ $divisionName }}
                    </p>
                @endif
            </div>
            <div class="grid grid-cols-2 gap-2 md:grid-cols-3 md:items-end">
                <div>
                    <label class="block text-[11px] font-medium text-zinc-600 dark:text-zinc-300">
                        {{ __('From') }}
                    </label>
                    <input
                        type="date"
                        wire:model.defer="date_from"
                        class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-[11px] text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                    >
                </div>
                <div>
                    <label class="block text-[11px] font-medium text-zinc-600 dark:text-zinc-300">
                        {{ __('To') }}
                    </label>
                    <input
                        type="date"
                        wire:model.defer="date_to"
                        class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-[11px] text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                    >
                </div>
                <div class="flex items-end">
                    <button
                        type="button"
                        wire:click="$refresh"
                        class="mt-1 inline-flex items-center rounded-full bg-zinc-900 px-3 py-1.5 text-[11px] font-medium text-white hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200"
                    >
                        {{ __('Apply') }}
                    </button>
                </div>
            </div>
        </div>
        <div class="mt-3 grid grid-cols-2 gap-3 md:grid-cols-4">
            <x-dashboard.card title="Team Submission" :value="$totalEntries"></x-dashboard.card>
            <x-dashboard.card title="Division Flags" :value="$flagsCount"></x-dashboard.card>
        </div>
        <div class="mt-4 grid gap-4 md:grid-cols-2">
            <div class="flex flex-col items-center" wire:ignore>
                <h3 class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">
                    {{ __('Team Submission') }}
                </h3>
                <canvas
                    class="mt-2 w-full max-w-xs h-40"
                    data-chart-type="pie-submissions-team"
                    data-labels='@json($submissionPieLabels)'
                    data-values='@json($submissionPieValues)'
                ></canvas>
                <p class="mt-2 text-[11px] text-zinc-500 dark:text-zinc-400 text-center">
                    {{ __('Submitted vs not submitted for the selected period (distinct reporting users).') }}
                </p>
                <div class="mt-1 flex gap-3 text-[11px] text-zinc-500 dark:text-zinc-400">
                    <span class="inline-flex items-center gap-1">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        {{ __('Submitted') }}
                    </span>
                    <span class="inline-flex items-center gap-1">
                        <span class="h-2 w-2 rounded-full bg-zinc-300 dark:bg-zinc-600"></span>
                        {{ __('Not submitted') }}
                    </span>
                </div>
            </div>
            <div class="flex flex-col items-center" wire:ignore>
                <h3 class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">
                    {{ __('Division Flags') }}
                </h3>
                <canvas
                    class="mt-2 w-full max-w-xs h-40"
                    data-chart-type="pie-flags"
                    data-labels='@json($flagLabels)'
                    data-values='@json($flagValues)'
                ></canvas>
                <p class="mt-2 text-[11px] text-zinc-500 dark:text-zinc-400 text-center">
                    {{ __('Flag distribution by severity for the selected period.') }}
                </p>
                <div class="mt-1 flex flex-wrap justify-center gap-3 text-[11px] text-zinc-500 dark:text-zinc-400">
                    @foreach ($flagLabels as $label)
                        <span class="inline-flex items-center gap-1">
                            <span class="h-2 w-2 rounded-full bg-zinc-300 dark:bg-zinc-600"></span>
                            {{ $label }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">
                {{ __('AI Summary') }}
            </h3>
            <button
                type="button"
                wire:click="generateSummary"
                class="inline-flex items-center rounded-full bg-zinc-900 px-3 py-1.5 text-[11px] font-medium text-white hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200"
            >
                {{ __('Generate Summary') }}
            </button>
        </div>
        <div class="mt-3">
            @if ($summary && $summary->summary)
                <div class="flex flex-col gap-1">
                    <p class="text-[11px] text-zinc-500 dark:text-zinc-400">
                        {{ __('Last generated on') }} {{ optional($summary->created_at)->format('Y-m-d H:i') }}
                    </p>
                    <p class="text-sm text-zinc-700 whitespace-pre-line dark:text-zinc-100">
                        {{ $summary->summary }}
                    </p>
                </div>
            @else
                <p class="text-xs text-zinc-500 dark:text-zinc-400">
                    {{ __('No summary has been generated yet.') }}
                </p>
            @endif
        </div>

        @if ($summaryHistory->isNotEmpty())
            <div class="mt-4 border-t border-zinc-800 pt-3">
                <h4 class="text-[11px] font-semibold text-zinc-500 dark:text-zinc-400">
                    {{ __('Recent summaries') }}
                </h4>
                <div class="mt-2 flex flex-col gap-2 max-h-56 overflow-y-auto">
                    @foreach ($summaryHistory as $item)
                        <details class="group rounded-lg bg-zinc-900/40 p-2" x-data>
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-2">
                                <div>
                                    <p class="text-[11px] text-zinc-400">
                                        {{ optional($item->date_from)->format('Y-m-d') }}
                                        @if ($item->date_to && $item->date_to !== $item->date_from)
                                            &ndash; {{ optional($item->date_to)->format('Y-m-d') }}
                                        @endif
                                        &middot;
                                        {{ __('Generated at') }} {{ optional($item->created_at)->format('Y-m-d H:i') }}
                                    </p>
                                    <p class="mt-1 text-[11px] text-zinc-200 line-clamp-2">
                                        {{ \Illuminate\Support\Str::limit($item->summary, 160) }}
                                    </p>
                                </div>
                                <span class="text-[10px] text-zinc-400 group-open:rotate-90 transition-transform">
                                    ›
                                </span>
                            </summary>
                            <div class="mt-2 text-[11px] text-zinc-200 whitespace-pre-line">
                                {{ $item->summary }}
                            </div>
                        </details>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
