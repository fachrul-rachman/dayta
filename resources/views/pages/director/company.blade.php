<div class="flex flex-1 flex-col gap-4 rounded-xl">
    <div class="rounded-xl border border-zinc-200 bg-white p-4 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">
                    {{ __('Company Overview') }}
                </h2>
                <p class="mt-1 text-[11px] text-zinc-500 dark:text-zinc-400">
                    {{ __('Monitor submission discipline, flags, and division comparison for the selected period.') }}
                </p>
            </div>
            <div class="flex gap-2">
                <div>
                    <label class="block text-[11px] font-medium text-zinc-600 dark:text-zinc-300">
                        {{ __('From') }}
                    </label>
                    <input
                        type="date"
                        wire:model.defer="date_from"
                        class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-sm text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                    >
                </div>
                <div>
                    <label class="block text-[11px] font-medium text-zinc-600 dark:text-zinc-300">
                        {{ __('To') }}
                    </label>
                    <input
                        type="date"
                        wire:model.defer="date_to"
                        class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-2 py-1 text-sm text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-50"
                    >
                </div>
                <div class="flex items-end">
                    <button
                        type="button"
                        wire:click="$refresh"
                        wire:loading.attr="disabled"
                        wire:target="$refresh"
                        class="inline-flex items-center rounded-full bg-zinc-900 px-3 py-1.5 text-[11px] font-medium text-white hover:bg-zinc-800 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200"
                    >
                        <span wire:loading.remove wire:target="$refresh">
                            {{ __('Apply') }}
                        </span>
                        <span wire:loading wire:target="$refresh">
                            {{ __('Loading...') }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="mt-3 grid grid-cols-2 gap-3 md:grid-cols-4">
            <x-dashboard.card
                title="Total Submitted Entries"
                :value="$totalSubmittedEntries"
                helper="Distinct reporting days with submission"
            ></x-dashboard.card>
            <x-dashboard.card
                title="Total Missing"
                :value="$totalMissingEntries"
                helper="Expected minus submitted entries"
            ></x-dashboard.card>
            <x-dashboard.card
                title="Total Flags"
                :value="$totalFlags"
                helper="Company and division flags"
            ></x-dashboard.card>
            <x-dashboard.card
                title="Divisions with High Flags"
                :value="$divisionsWithHighFlags"
                helper="Divisions with high-severity flags"
            ></x-dashboard.card>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div class="rounded-xl border border-zinc-200 bg-white p-4 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <h3 class="text-xs font-semibold text-zinc-900 dark:text-zinc-50">
                {{ __('Submission Trend') }}
            </h3>
            <p class="mt-1 text-[11px] text-zinc-500 dark:text-zinc-400">
                {{ __('Submitted vs missing entries per day.') }}
            </p>
            <div class="mt-3" wire:ignore>
                <canvas
                    class="w-full h-48"
                    data-chart-type="line-company-submissions"
                    data-labels='@json($submissionTrendLabels)'
                    data-submitted='@json($submissionTrendSubmitted)'
                    data-missing='@json($submissionTrendMissing)'
                ></canvas>
            </div>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-4 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <h3 class="text-xs font-semibold text-zinc-900 dark:text-zinc-50">
                {{ __('Flag Severity Trend') }}
            </h3>
            <p class="mt-1 text-[11px] text-zinc-500 dark:text-zinc-400">
                {{ __('Low, medium, and high severity flags over time.') }}
            </p>
            <div class="mt-3" wire:ignore>
                <canvas
                    class="w-full h-48"
                    data-chart-type="bar-flags-trend"
                    data-labels='@json($flagsTrendLabels)'
                    data-low='@json($flagsTrendLow)'
                    data-medium='@json($flagsTrendMedium)'
                    data-high='@json($flagsTrendHigh)'
                ></canvas>
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <h3 class="text-xs font-semibold text-zinc-900 dark:text-zinc-50">
            {{ __('Division Comparison') }}
        </h3>
        <p class="mt-1 text-[11px] text-zinc-500 dark:text-zinc-400">
            {{ __('Flags and submission health per division.') }}
        </p>
        <div class="mt-3" wire:ignore>
            <canvas
                class="w-full h-56"
                data-chart-type="bar-division-comparison"
                data-labels='@json($divisionLabels)'
                data-flags='@json($divisionFlagsValues)'
                data-rates='@json($divisionSubmissionRates)'
            ></canvas>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-[2fr,1.5fr]">
        <div class="rounded-xl border border-zinc-200 bg-white p-4 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <h3 class="text-xs font-semibold text-zinc-900 dark:text-zinc-50">
                {{ __('Company Findings') }}
            </h3>
            @if ($topFlags->isEmpty())
                <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">
                    {{ __('No findings have been recorded for the selected period.') }}
                </p>
            @else
                <div class="mt-3 flex flex-col gap-2">
                    @foreach ($topFlags as $flag)
                        <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-2 text-[11px] dark:border-zinc-700 dark:bg-zinc-900/60">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-semibold text-zinc-800 dark:text-zinc-100">
                                    {{ $flag->title }}
                                </span>
                                @php
                                    $severity = $flag->severity?->value;
                                    $severityClass = match ($severity) {
                                        'high' => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-100',
                                        'medium' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-100',
                                        default => 'bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-100',
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-medium {{ $severityClass }}">
                                    {{ ucfirst($flag->severity?->value ?? 'unknown') }}
                                </span>
                            </div>
                            <p class="mt-1 text-zinc-600 dark:text-zinc-200">
                                {{ $flag->details }}
                            </p>
                            <p class="mt-1 text-[10px] text-zinc-500 dark:text-zinc-400">
                                {{ optional($flag->flagged_at)->format('Y-m-d') }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-4 text-xs shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">
                        {{ __('AI Summary') }}
                    </h3>
                    <button
                        type="button"
                        wire:click="generateSummary"
                        wire:loading.attr="disabled"
                        wire:target="generateSummary"
                        class="inline-flex items-center rounded-full bg-zinc-900 px-3 py-1.5 text-[11px] font-medium text-white hover:bg-zinc-800 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200"
                    >
                        <span wire:loading.remove wire:target="generateSummary">
                            {{ __('Generate Summary') }}
                        </span>
                        <span wire:loading wire:target="generateSummary">
                            {{ __('Generating...') }}
                        </span>
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
                        {{ __('AI summary is not available yet for this period.') }}
                    </p>
                @endif
            </div>

            @if ($summaryHistory->isNotEmpty())
                <div class="mt-4 border-t border-zinc-800 pt-3">
                    <h4 class="text-[11px] font-semibold text-zinc-500 dark:text-zinc-400">
                        {{ __('Recent summaries') }}
                    </h4>
                    <div class="mt-2 flex max-h-44 flex-col gap-2 overflow-y-auto">
                        @foreach ($summaryHistory as $item)
                            <details class="group rounded-lg bg-zinc-900/40 p-2">
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
</div>
