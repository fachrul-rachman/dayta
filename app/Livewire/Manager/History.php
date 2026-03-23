<?php

namespace App\Livewire\Manager;

use App\Models\DailyEntry;
use Livewire\Component;

class History extends Component
{
    public ?string $from = null;
    public ?string $to = null;

    public function mount(): void
    {
        $today = now()->toDateString();
        $this->from = $today;
        $this->to = $today;
    }

    public function render()
    {
        $user = auth()->user();

        $query = DailyEntry::query()->where('user_id', $user->id)->orderByDesc('entry_date');

        if ($this->from) {
            $query->whereDate('entry_date', '>=', $this->from);
        }

        if ($this->to) {
            $query->whereDate('entry_date', '<=', $this->to);
        }

        $entries = $query->with('items')->limit(60)->get();

        return view('pages.manager.history', [
            'entries' => $entries,
        ])->layout('layouts.app');
    }
}
