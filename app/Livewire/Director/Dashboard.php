<?php

namespace App\Livewire\Director;

use App\Enums\UserRole;
use App\Models\DailyEntry;
use App\Models\Division;
use App\Models\Flag;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $today = now()->toDateString();

        $companyEntriesToday = DailyEntry::whereDate('entry_date', $today)
            ->where(function ($q) {
                $q->where('plan_status', 'submitted')
                    ->orWhere('realization_status', 'submitted');
            })
            ->count();

        $totalReporters = User::query()
            ->whereIn('role', [UserRole::Manager, UserRole::Hod])
            ->where('is_active', true)
            ->count();

        $companySubmissionRate = $totalReporters > 0
            ? round(($companyEntriesToday / $totalReporters) * 100)
            : null;

        $companyFlags = Flag::query()->count();

        $divisions = Division::orderBy('name')->get();

        $divisionStats = [];

        if ($divisions->isNotEmpty()) {
            $entriesPerDivision = DailyEntry::query()
                ->whereDate('entry_date', $today)
                ->selectRaw('division_id, count(distinct user_id) as submitted_users')
                ->groupBy('division_id')
                ->pluck('submitted_users', 'division_id');

            $flagsPerDivision = Flag::query()
                ->where('scope_type', 'division')
                ->whereDate('flagged_at', '>=', $today)
                ->selectRaw('scope_id as division_id, count(*) as total')
                ->groupBy('scope_id')
                ->pluck('total', 'division_id');

            $reportersPerDivision = User::query()
                ->whereIn('role', [UserRole::Manager, UserRole::Hod])
                ->where('is_active', true)
                ->selectRaw('division_id, count(*) as total')
                ->groupBy('division_id')
                ->pluck('total', 'division_id');

            foreach ($divisions as $division) {
                $reporters = (int) ($reportersPerDivision[$division->id] ?? 0);
                $submitted = (int) ($entriesPerDivision[$division->id] ?? 0);
                $flags = (int) ($flagsPerDivision[$division->id] ?? 0);

                $rate = $reporters > 0 ? round(($submitted / $reporters) * 100) : null;

                $divisionStats[] = [
                    'division' => $division,
                    'reporters' => $reporters,
                    'submitted' => $submitted,
                    'submission_rate' => $rate,
                    'flags' => $flags,
                ];
            }
        }

        $divisionNeedingAttention = collect($divisionStats)
            ->sortByDesc('flags')
            ->sortBy(function ($stat) {
                return $stat['submission_rate'] ?? 101;
            })
            ->first();

        return view('pages.director.dashboard', [
            'companyEntriesToday' => $companyEntriesToday,
            'companySubmissionRate' => $companySubmissionRate,
            'companyFlags' => $companyFlags,
            'divisionNeedingAttention' => $divisionNeedingAttention,
        ])->layout('layouts.app');
    }
}
