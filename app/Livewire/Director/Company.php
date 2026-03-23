<?php

namespace App\Livewire\Director;

use App\Enums\FlagSeverity;
use App\Enums\UserRole;
use App\Models\AiSummary;
use App\Models\DailyEntry;
use App\Models\DailyEntryItem;
use App\Models\Division;
use App\Models\Flag;
use App\Models\User;
use App\Services\AiSummaryService;
use Livewire\Component;

class Company extends Component
{
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
        $user = auth()->user();

        $payload = [
            'scope_type' => 'company',
            'scope_id' => null,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'filters' => [],
        ];

        $this->summary = $service->generate($payload, $user);
    }

    public function render()
    {
        $entriesQuery = DailyEntry::query();

        if ($this->date_from) {
            $entriesQuery->whereDate('entry_date', '>=', $this->date_from);
        }

        if ($this->date_to) {
            $entriesQuery->whereDate('entry_date', '<=', $this->date_to);
        }

        $entries = $entriesQuery->get();

        $reportersQuery = User::query()
            ->whereIn('role', [UserRole::Manager, UserRole::Hod])
            ->where('is_active', true);

        $totalReporters = $reportersQuery->count();

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
            ->when($this->date_from, fn ($q) => $q->whereDate('entry_date', '>=', $this->date_from))
            ->when($this->date_to, fn ($q) => $q->whereDate('entry_date', '<=', $this->date_to))
            ->where(function ($q) {
                $q->where('plan_status', 'submitted')
                    ->orWhere('realization_status', 'submitted');
            })
            ->selectRaw('entry_date, count(distinct user_id) as total')
            ->groupBy('entry_date')
            ->pluck('total', 'entry_date');

        $submissionTrendLabels = [];
        $submissionTrendSubmitted = [];
        $submissionTrendMissing = [];

        $totalSubmitted = 0;
        $expectedTotal = 0;

        foreach ($period as $date) {
            $submitted = (int) ($submittedPerDay[$date] ?? 0);
            $expected = $totalReporters;
            $missing = max($expected - $submitted, 0);

            $submissionTrendLabels[] = now()->parse($date)->format('d M');
            $submissionTrendSubmitted[] = $submitted;
            $submissionTrendMissing[] = $missing;

            $totalSubmitted += $submitted;
            $expectedTotal += $expected;
        }

        $totalMissing = max($expectedTotal - $totalSubmitted, 0);

        $totalFlags = Flag::query()
            ->when($this->date_from, fn ($q) => $q->whereDate('flagged_at', '>=', $this->date_from))
            ->when($this->date_to, fn ($q) => $q->whereDate('flagged_at', '<=', $this->date_to))
            ->count();

        $flagsBySeverityPerDay = Flag::query()
            ->when($this->date_from, fn ($q) => $q->whereDate('flagged_at', '>=', $this->date_from))
            ->when($this->date_to, fn ($q) => $q->whereDate('flagged_at', '<=', $this->date_to))
            ->selectRaw('DATE(flagged_at) as day, severity, count(*) as total')
            ->groupBy('day', 'severity')
            ->get()
            ->groupBy('day');

        $flagsTrendLow = [];
        $flagsTrendMedium = [];
        $flagsTrendHigh = [];

        foreach ($period as $date) {
            $bySeverity = $flagsBySeverityPerDay[$date] ?? collect();
            $low = $bySeverity->firstWhere('severity', FlagSeverity::Low->value)->total ?? 0;
            $medium = $bySeverity->firstWhere('severity', FlagSeverity::Medium->value)->total ?? 0;
            $high = $bySeverity->firstWhere('severity', FlagSeverity::High->value)->total ?? 0;

            $flagsTrendLow[] = $low;
            $flagsTrendMedium[] = $medium;
            $flagsTrendHigh[] = $high;
        }

        $divisions = Division::query()->orderBy('name')->get();

        $divisionFlags = Flag::query()
            ->where('scope_type', 'division')
            ->when($this->date_from, fn ($q) => $q->whereDate('flagged_at', '>=', $this->date_from))
            ->when($this->date_to, fn ($q) => $q->whereDate('flagged_at', '<=', $this->date_to))
            ->selectRaw('scope_id as division_id, count(*) as total')
            ->groupBy('scope_id')
            ->pluck('total', 'division_id');

        $reportersPerDivision = User::query()
            ->whereIn('role', [UserRole::Manager, UserRole::Hod])
            ->where('is_active', true)
            ->selectRaw('division_id, count(*) as total')
            ->groupBy('division_id')
            ->pluck('total', 'division_id');

        $submittedPerDivisionPeriod = DailyEntry::query()
            ->when($this->date_from, fn ($q) => $q->whereDate('entry_date', '>=', $this->date_from))
            ->when($this->date_to, fn ($q) => $q->whereDate('entry_date', '<=', $this->date_to))
            ->where(function ($q) {
                $q->where('plan_status', 'submitted')
                    ->orWhere('realization_status', 'submitted');
            })
            ->selectRaw('division_id, count(DISTINCT (user_id, entry_date)) as total')
            ->groupBy('division_id')
            ->pluck('total', 'division_id');

        $daysCount = max(count($period), 1);

        $divisionLabels = [];
        $divisionFlagsValues = [];
        $divisionSubmissionRates = [];

        $divisionsWithHighFlags = 0;

        foreach ($divisions as $division) {
            $divisionLabels[] = $division->name;
            $flags = (int) ($divisionFlags[$division->id] ?? 0);
            $divisionFlagsValues[] = $flags;

            if ($flags > 0 && $flags >= 1 && Flag::query()
                ->where('scope_type', 'division')
                ->where('scope_id', $division->id)
                ->where('severity', FlagSeverity::High->value)
                ->when($this->date_from, fn ($q) => $q->whereDate('flagged_at', '>=', $this->date_from))
                ->when($this->date_to, fn ($q) => $q->whereDate('flagged_at', '<=', $this->date_to))
                ->exists()) {
                $divisionsWithHighFlags++;
            }

            $reporters = (int) ($reportersPerDivision[$division->id] ?? 0);
            $submittedDistinct = (int) ($submittedPerDivisionPeriod[$division->id] ?? 0);
            $expectedDivision = $reporters * $daysCount;
            $rate = $expectedDivision > 0 ? round(($submittedDistinct / $expectedDivision) * 100) : 0;
            $divisionSubmissionRates[] = $rate;
        }

        $topFlags = Flag::query()
            ->when($this->date_from, fn ($q) => $q->whereDate('flagged_at', '>=', $this->date_from))
            ->when($this->date_to, fn ($q) => $q->whereDate('flagged_at', '<=', $this->date_to))
            ->orderByRaw("case severity when ? then 3 when ? then 2 when ? then 1 else 0 end desc", [
                FlagSeverity::High->value,
                FlagSeverity::Medium->value,
                FlagSeverity::Low->value,
            ])
            ->orderByDesc('flagged_at')
            ->limit(10)
            ->get();

        $summaryHistory = AiSummary::query()
            ->where('scope_type', 'company')
            ->whereNull('scope_id')
            ->orderByDesc('date_from')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $this->dispatch('init-charts');

        return view('pages.director.company', [
            'totalSubmittedEntries' => $totalSubmitted,
            'totalMissingEntries' => $totalMissing,
            'totalFlags' => $totalFlags,
            'divisionsWithHighFlags' => $divisionsWithHighFlags,
            'submissionTrendLabels' => $submissionTrendLabels,
            'submissionTrendSubmitted' => $submissionTrendSubmitted,
            'submissionTrendMissing' => $submissionTrendMissing,
            'flagsTrendLabels' => $submissionTrendLabels,
            'flagsTrendLow' => $flagsTrendLow,
            'flagsTrendMedium' => $flagsTrendMedium,
            'flagsTrendHigh' => $flagsTrendHigh,
            'divisionLabels' => $divisionLabels,
            'divisionFlagsValues' => $divisionFlagsValues,
            'divisionSubmissionRates' => $divisionSubmissionRates,
            'topFlags' => $topFlags,
            'summary' => $this->summary,
            'summaryHistory' => $summaryHistory,
        ])->layout('layouts.app');
    }
}
