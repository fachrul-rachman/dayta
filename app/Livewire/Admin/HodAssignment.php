<?php

namespace App\Livewire\Admin;

use App\Enums\UserRole;
use App\Models\Division;
use App\Models\DivisionHodAssignment;
use App\Models\User;
use Livewire\Component;

class HodAssignment extends Component
{
    public ?int $division_id = null;

    public ?int $hod_user_id = null;

    public function save(): void
    {
        $data = $this->validate([
            'division_id' => ['required', 'integer', 'exists:divisions,id'],
            'hod_user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $hod = User::findOrFail($data['hod_user_id']);

        if ($hod->role !== UserRole::Hod) {
            $this->addError('hod_user_id', __('Selected user is not a HoD.'));

            return;
        }

        // Deactivate existing active assignment for division.
        DivisionHodAssignment::where('division_id', $data['division_id'])
            ->where('is_active', true)
            ->update(['is_active' => false, 'ends_at' => now()]);

        DivisionHodAssignment::create([
            'division_id' => $data['division_id'],
            'hod_user_id' => $data['hod_user_id'],
            'is_active' => true,
            'starts_at' => now(),
        ]);

        $this->reset(['division_id', 'hod_user_id']);
        $this->resetErrorBag();

        $this->dispatch('toast', message: __('HoD assignment saved.'), type: 'success');
    }

    public function render()
    {
        $divisions = Division::orderBy('name')->get();

        $activeAssignments = DivisionHodAssignment::with(['division', 'hod'])
            ->where('is_active', true)
            ->get();

        $hods = User::where('role', UserRole::Hod)->where('is_active', true)->orderBy('name')->get();

        return view('pages.admin.hod-assignment', [
            'divisions' => $divisions,
            'hods' => $hods,
            'activeAssignments' => $activeAssignments,
        ])->layout('layouts.app');
    }
}
