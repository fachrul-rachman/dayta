<?php

namespace App\Livewire\Hod;

use App\Enums\FlagSeverity;
use App\Enums\UserRole;
use App\Models\AiSummary;
use App\Models\DailyEntry;
use App\Models\Flag;
use App\Models\User;
use App\Services\AiSummaryService;
use Livewire\Component;

class DivisionSummary extends Component
{
    public ?string $date_from = null;

    public ?string $date_to = null;

    public ?AiSummary $summary = null;

    public function mount(): void
    {
        $today = now()->toDateString();
        $this->date_from = $today;
        $this->date_to = $today;
    }

    public function generateSummary(AiSummaryService $service): void
    {
        $user = auth()->user();

        $payload = [
            'scope_type' => 'division',
            'scope_id' => $user->division_id,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'filters' => [],
        ];

        $this->summary = $service->generate($payload, $user);
    }

    public function render()
    {
        $user = auth()->user();

        $entriesQuery = DailyEntry::query()
            ->where('division_id', $user->division_id);

        if ($this->date_from) {
            $entriesQuery->whereDate('entry_date', '>=', $this->date_from);
        }

        if ($this->date_to) {
            $entriesQuery->whereDate('entry_date', '<=', $this->date_to);
        }

        $totalEntries = $entriesQuery->count();

        // Team submission breakdown (submitted vs not submitted) for reporters in this division
        $reportersQuery = User::query()
            ->where('division_id', $user->division_id)
            ->whereIn('role', [UserRole::Manager, UserRole::Hod]);

        $totalReporters = $reportersQuery->count();

        $submittedUserIds = (clone $entriesQuery)
            ->distinct('user_id')
            ->pluck('user_id');

        $submittedCount = $submittedUserIds->count();
        if ($totalReporters > 0 && $submittedCount > $totalReporters) {
            $submittedCount = $totalReporters;
        }

        $notSubmittedCount = max($totalReporters - $submittedCount, 0);

        $submissionPieLabels = ['Submitted', 'Not Submitted'];
        $submissionPieValues = [$submittedCount, $notSubmittedCount];

        $flagsQuery = Flag::query()
            ->where('scope_type', 'division')
            ->where('scope_id', $user->division_id);

        if ($this->date_from) {
            $flagsQuery->whereDate('flagged_at', '>=', $this->date_from);
        }

        if ($this->date_to) {
            $flagsQuery->whereDate('flagged_at', '<=', $this->date_to);
        }

        $flagsCount = $flagsQuery->count();

        $flagsBySeverity = (clone $flagsQuery)
            ->selectRaw('severity, count(*) as total')
            ->groupBy('severity')
            ->pluck('total', 'severity');

        $flagLabels = [];
        $flagValues = [];

        foreach (FlagSeverity::cases() as $case) {
            $flagLabels[] = ucfirst($case->value);
            $flagValues[] = (int) ($flagsBySeverity[$case->value] ?? 0);
        }

        $history = AiSummary::query()
            ->where('scope_type', 'division')
            ->where('scope_id', $user->division_id)
            ->orderByDesc('date_from')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('pages.hod.division-summary', [
            'totalEntries' => $totalEntries,
            'flagsCount' => $flagsCount,
            'summary' => $this->summary,
            'submissionPieLabels' => $submissionPieLabels,
            'submissionPieValues' => $submissionPieValues,
            'flagLabels' => $flagLabels,
            'flagValues' => $flagValues,
            'divisionName' => $user->division?->name,
            'summaryHistory' => $history,
        ])->layout('layouts.app');
    }
}
