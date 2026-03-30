<?php

namespace App\Livewire\Hod;

use App\Models\DailyEntry;
use Livewire\Component;

class DivisionEntries extends Component
{
    public ?string $date_from = null;

    public ?string $date_to = null;

    public function mount(): void
    {
        $today = now()->toDateString();
        $this->date_from = $today;
        $this->date_to = $today;
    }

    public function render()
    {
        $user = auth()->user();

        $query = DailyEntry::query()
            ->with(['user', 'items.attachments'])
            ->where('division_id', $user->division_id)
            ->orderByDesc('entry_date');

        if ($this->date_from) {
            $query->whereDate('entry_date', '>=', $this->date_from);
        }

        if ($this->date_to) {
            $query->whereDate('entry_date', '<=', $this->date_to);
        }

        $entries = $query->limit(100)->get();

        return view('pages.hod.division-entries', [
            'entries' => $entries,
        ])->layout('layouts.app');
    }
}
