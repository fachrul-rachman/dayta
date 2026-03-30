<?php

namespace App\Livewire\Hod;

use App\Enums\BigRockStatus;
use App\Models\BigRock;
use Livewire\Component;

class BigRockManagement extends Component
{
    public ?int $editingId = null;

    public string $title = '';

    public ?string $description = null;

    public string $status = BigRockStatus::Active->value;

    public ?string $period_start = null;

    public ?string $period_end = null;

    public function createNew(): void
    {
        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $user = auth()->user();

        $bigRock = BigRock::query()
            ->where('division_id', $user->division_id)
            ->findOrFail($id);

        $this->editingId = $bigRock->id;
        $this->title = $bigRock->title;
        $this->description = $bigRock->description;
        $this->status = $bigRock->status->value;
        $this->period_start = optional($bigRock->period_start)->toDateString();
        $this->period_end = optional($bigRock->period_end)->toDateString();
    }

    public function save(): void
    {
        $user = auth()->user();

        $data = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:'.implode(',', array_column(BigRockStatus::cases(), 'value'))],
            'period_start' => ['nullable', 'date'],
            'period_end' => ['nullable', 'date', 'after_or_equal:period_start'],
        ]);

        $payload = [
            'division_id' => $user->division_id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'],
            'period_start' => $data['period_start'] ?: null,
            'period_end' => $data['period_end'] ?: null,
        ];

        if ($this->editingId) {
            BigRock::query()
                ->where('division_id', $user->division_id)
                ->whereKey($this->editingId)
                ->update($payload);

            $this->dispatch('toast', message: __('Big Rock updated.'), type: 'success');
        } else {
            $bigRock = BigRock::create($payload);
            $this->editingId = $bigRock->id;

            $this->dispatch('toast', message: __('Big Rock created.'), type: 'success');
        }
    }

    public function archive(int $id): void
    {
        $user = auth()->user();

        BigRock::query()
            ->where('division_id', $user->division_id)
            ->whereKey($id)
            ->update(['status' => BigRockStatus::Archived]);

        $this->dispatch('toast', message: __('Big Rock archived.'), type: 'success');
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->title = '';
        $this->description = null;
        $this->status = BigRockStatus::Active->value;
        $this->period_start = null;
        $this->period_end = null;
    }

    public function render()
    {
        $user = auth()->user();

        $bigRocks = BigRock::query()
            ->where('division_id', $user->division_id)
            ->orderBy('status')
            ->orderBy('title')
            ->get();

        return view('pages.hod.big-rocks', [
            'bigRocks' => $bigRocks,
        ])->layout('layouts.app');
    }
}
