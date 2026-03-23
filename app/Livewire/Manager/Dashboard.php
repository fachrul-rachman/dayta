<?php

namespace App\Livewire\Manager;

use App\Models\DailyEntry;
use App\Models\Flag;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        $today = now()->toDateString();

        $todayEntry = DailyEntry::where('user_id', $user->id)
            ->whereDate('entry_date', $today)
            ->first();

        $latestEntry = DailyEntry::where('user_id', $user->id)
            ->orderByDesc('entry_date')
            ->first();

        $flagsCount = Flag::where('scope_type', 'user')
            ->where('scope_id', $user->id)
            ->whereDate('flagged_at', $today)
            ->count();

        return view('pages.manager.dashboard', [
            'todayEntry' => $todayEntry,
            'latestEntry' => $latestEntry,
            'flagsCount' => $flagsCount,
        ])->layout('layouts.app');
    }
}
