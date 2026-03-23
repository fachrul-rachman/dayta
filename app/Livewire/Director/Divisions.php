<?php

namespace App\Livewire\Director;

use App\Enums\FlagSeverity;
use App\Enums\WorkType;
use App\Models\AiSummary;
use App\Models\DailyEntry;
use App\Models\DailyEntryItem;
use App\Models\Division;
use App\Models\Flag;
use App\Services\AiSummaryService;
use Livewire\Component;

class Divisions extends Component
{
    public ?int $division_id = null;
    public ?string $date_from = null;
    public ?string $date_to = null;
    public ?AiSummary $summary = null;

    public function mount(): void
    {
        $today = now();
        $this->date_to = $today->toDateString();
        $this->date_from = $today->copy()->subDays(6)->toDateString();
    }

    public function generateSummary(AiSummaryService $service): void
    {
        if (! $this->division_id) {
            return;
        }

        $user = auth()->user();

        $payload = [
            'scope_type' => 'division',
            'scope_id' => $this->division_id,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'filters' => [],
        ];

        $this->summary = $service->generate($payload, $user);
    }

    public function render()
    {
        $divisions = Division::orderBy('name')->get();

        $selectedDivision = $this->division_id
            ? $divisions->firstWhere('id', $this->division_id)
            : null;

        $totalEntries = 0;
        $flagsCount = 0;
        $submissionHealth = null;
        $workMix = [
            'big_rock' => 0,
            'operational' => 0,
            'ad_hoc' => 0,
        ];
        $bigRockAlignment = null;

        $submissionTrendLabels = [];
        $submissionTrendSubmitted = [];
        $submissionTrendMissing = [];

        $workloadLabels = [];
        $workloadValues = [];

        $alignmentLabels = [];
        $alignmentWithBigRock = [];
        $alignmentWithoutBigRock = [];

        if ($selectedDivision) {
            $entriesQuery = DailyEntry::where('division_id', $selectedDivision->id);

            if ($this->date_from) {
                $entriesQuery->whereDate('entry_date', '>=', $this->date_from);
            }

            if ($this->date_to) {
                $entriesQuery->whereDate('entry_date', '<=', $this->date_to);
            }

            $entries = $entriesQuery->get();
            $totalEntries = $entries->count();

            $period = [];
            if ($this->date_from && $this->date_to) {
                $cursor = now()->parse($this->date_from)->startOfDay();
                $end = now()->parse($this->date_to)->startOfDay();

                while ($cursor->lte($end)) {
                    $period[] = $cursor->toDateString();
                    $cursor = $cursor->addDay();
                }
            }

            $submittedPerDay = DailyEntry::query()
                ->where('division_id', $selectedDivision->id)
                ->when($this->date_from, fn ($q) => $q->whereDate('entry_date', '>=', $this->date_from))
                ->when($this->date_to, fn ($q) => $q->whereDate('entry_date', '<=', $this->date_to))
                ->where(function ($q) {
                    $q->where('plan_status', 'submitted')
                        ->orWhere('realization_status', 'submitted');
                })
                ->selectRaw('entry_date, count(distinct user_id) as total')
                ->groupBy('entry_date')
                ->pluck('total', 'entry_date');

            $divisionReporters = $selectedDivision->users()
                ->whereIn('role', [\App\Enums\UserRole::Manager, \App\Enums\UserRole::Hod])
                ->where('is_active', true)
                ->count();

            $totalSubmitted = 0;
            $expectedTotal = 0;

            foreach ($period as $date) {
                $submitted = (int) ($submittedPerDay[$date] ?? 0);
                $expected = $divisionReporters;
                $missing = max($expected - $submitted, 0);

                $submissionTrendLabels[] = now()->parse($date)->format('d M');
                $submissionTrendSubmitted[] = $submitted;
                $submissionTrendMissing[] = $missing;

                $totalSubmitted += $submitted;
                $expectedTotal += $expected;
            }

            if ($expectedTotal > 0) {
                $submissionHealth = round(($totalSubmitted / $expectedTotal) * 100);
            }

            $flagsQuery = Flag::where('scope_type', 'division')
                ->where('scope_id', $selectedDivision->id);

            if ($this->date_from) {
                $flagsQuery->whereDate('flagged_at', '>=', $this->date_from);
            }

            if ($this->date_to) {
                $flagsQuery->whereDate('flagged_at', '<=', $this->date_to);
            }

            $flagsCount = $flagsQuery->count();

            $itemsQuery = DailyEntryItem::query()
                ->whereHas('dailyEntry', function ($q) use ($selectedDivision) {
                    $q->where('division_id', $selectedDivision->id);
                })
                ->when($this->date_from, function ($q) {
                    $q->whereHas('dailyEntry', fn ($sub) => $sub->whereDate('entry_date', '>=', $this->date_from));
                })
                ->when($this->date_to, function ($q) {
                    $q->whereHas('dailyEntry', fn ($sub) => $sub->whereDate('entry_date', '<=', $this->date_to));
                });

            $workTypeCounts = $itemsQuery->selectRaw('work_type, count(*) as total')
                ->groupBy('work_type')
                ->pluck('total', 'work_type');

            $totalItems = $workTypeCounts->sum();

            if ($totalItems > 0) {
                $workMix['big_rock'] = (int) ($workTypeCounts[WorkType::BigRock->value] ?? 0);
                $workMix['operational'] = (int) ($workTypeCounts[WorkType::Operational->value] ?? 0);
                $workMix['ad_hoc'] = (int) ($workTypeCounts[WorkType::AdHoc->value] ?? 0);

                $workloadLabels = ['Big Rock', 'Operational', 'Ad Hoc'];
                $workloadValues = array_values($workMix);

                $withBigRock = $workMix['big_rock'];
                $withoutBigRock = $totalItems - $withBigRock;

                $alignmentLabels = ['Aligned to Big Rocks', 'Not Linked to Big Rocks'];
                $alignmentWithBigRock = [$withBigRock];
                $alignmentWithoutBigRock = [$withoutBigRock];

                $bigRockAlignment = round(($withBigRock / $totalItems) * 100);
            }

            if (empty($submissionTrendLabels)) {
                $submissionTrendLabels = [];
                $submissionTrendSubmitted = [];
                $submissionTrendMissing = [];
            }
        }

        $divisionFlags = collect();
        $summaryHistory = collect();

        if ($selectedDivision) {
            $divisionFlags = $flagsQuery
                ->orderByRaw("case severity when ? then 3 when ? then 2 when ? then 1 else 0 end desc", [
                    FlagSeverity::High->value,
                    FlagSeverity::Medium->value,
                    FlagSeverity::Low->value,
                ])
                ->orderByDesc('flagged_at')
                ->limit(10)
                ->get();

            $summaryHistory = AiSummary::query()
                ->where('scope_type', 'division')
                ->where('scope_id', $selectedDivision->id)
                ->orderByDesc('date_from')
                ->orderByDesc('created_at')
                ->limit(10)
                ->get();
        }

        $this->dispatch('init-charts');

        return view('pages.director.divisions', [
            'divisions' => $divisions,
            'selectedDivision' => $selectedDivision,
            'totalEntries' => $totalEntries,
            'flagsCount' => $flagsCount,
            'submissionHealth' => $submissionHealth,
            'workMix' => $workMix,
            'bigRockAlignment' => $bigRockAlignment,
            'submissionTrendLabels' => $submissionTrendLabels,
            'submissionTrendSubmitted' => $submissionTrendSubmitted,
            'submissionTrendMissing' => $submissionTrendMissing,
            'workloadLabels' => $workloadLabels,
            'workloadValues' => $workloadValues,
            'alignmentLabels' => $alignmentLabels,
            'alignmentWithBigRock' => $alignmentWithBigRock,
            'alignmentWithoutBigRock' => $alignmentWithoutBigRock,
            'divisionFlags' => $divisionFlags,
            'summary' => $this->summary,
            'summaryHistory' => $summaryHistory,
        ])->layout('layouts.app');
    }
}
