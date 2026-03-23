<?php

namespace App\Livewire\Hod;

use App\Models\DailyEntry;
use App\Models\Flag;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        $today = now()->toDateString();

        $ownToday = DailyEntry::where('user_id', $user->id)
            ->whereDate('entry_date', $today)
            ->with('items')
            ->first();

        $divisionEntries = DailyEntry::query()
            ->where('division_id', $user->division_id)
            ->whereDate('entry_date', $today)
            ->count();

        $divisionFlags = Flag::query()
            ->where('scope_type', 'division')
            ->where('scope_id', $user->division_id)
            ->whereDate('flagged_at', $today)
            ->count();

        return view('pages.hod.dashboard', [
            'ownToday' => $ownToday,
            'divisionEntriesCount' => $divisionEntries,
            'divisionFlagsCount' => $divisionFlags,
        ])->layout('layouts.app');
    }
}
