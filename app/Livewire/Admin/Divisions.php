<?php

namespace App\Livewire\Admin;

use App\Models\Division;
use Livewire\Component;

class Divisions extends Component
{
    public ?int $editingId = null;

    public string $name = '';
    public bool $is_active = true;

    public function edit(int $id): void
    {
        $division = Division::findOrFail($id);
        $this->editingId = $division->id;
        $this->name = $division->name;
        $this->is_active = $division->is_active;
    }

    public function create(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        if ($this->editingId) {
            $division = Division::findOrFail($this->editingId);
            $division->update($data);
        } else {
            Division::create($data);
        }

        $this->create();
    }

    public function render()
    {
        return view('pages.admin.divisions', [
            'divisions' => Division::orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}
