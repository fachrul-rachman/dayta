<?php

namespace App\Livewire\Admin;

use App\Models\AdminOverride as AdminOverrideModel;
use App\Models\DailyEntry;
use App\Models\DailyEntryItem;
use App\Models\Division;
use App\Models\User;
use Livewire\Component;

class Override extends Component
{
    public ?string $date_from = null;

    public ?string $date_to = null;

    public ?int $division_id = null;

    public ?int $user_id = null;

    public ?int $target_entry_id = null;

    public ?int $target_item_id = null;

    public string $new_text = '';

    public string $reason = '';

    public function mount(): void
    {
        $today = now()->toDateString();
        $this->date_from = $today;
        $this->date_to = $today;
    }

    public function selectTarget(int $id): void
    {
        $this->target_entry_id = $id;
        $this->target_item_id = null;
        $this->new_text = '';
        $this->reason = '';
        $this->resetErrorBag();
    }

    public function submitOverride(): void
    {
        $this->validate([
            'target_entry_id' => ['required', 'integer', 'exists:daily_entries,id'],
            'target_item_id' => ['required', 'integer', 'exists:daily_entry_items,id'],
            'new_text' => ['required', 'string'],
            'reason' => ['required', 'string', 'min:5'],
        ]);

        $entry = DailyEntry::findOrFail($this->target_entry_id);
        $item = DailyEntryItem::where('id', $this->target_item_id)
            ->where('daily_entry_id', $entry->id)
            ->firstOrFail();

        $old = $item->description;

        $item->description = $this->new_text;
        $item->save();

        AdminOverrideModel::create([
            'admin_user_id' => auth()->id(),
            'target_type' => DailyEntryItem::class,
            'target_id' => $item->id,
            'field' => 'description',
            'old_value' => (string) $old,
            'new_value' => (string) $this->new_text,
            'reason' => $this->reason,
            'overridden_at' => now(),
        ]);

        $this->target_item_id = null;
        $this->new_text = '';
        $this->reason = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        $query = DailyEntry::query()
            ->with(['user', 'division'])
            ->orderByDesc('entry_date');

        if ($this->date_from) {
            $query->whereDate('entry_date', '>=', $this->date_from);
        }

        if ($this->date_to) {
            $query->whereDate('entry_date', '<=', $this->date_to);
        }

        if ($this->division_id) {
            $query->where('division_id', $this->division_id);
        }

        if ($this->user_id) {
            $query->where('user_id', $this->user_id);
        }

        $entries = $query->with('items')->limit(50)->get();

        return view('pages.admin.override', [
            'divisions' => Division::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
            'entries' => $entries,
        ])->layout('layouts.app');
    }
}
