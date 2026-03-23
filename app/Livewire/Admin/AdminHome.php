<?php

namespace App\Livewire\Admin;

use App\Models\Division;
use App\Models\DivisionHodAssignment;
use App\Models\ReportSetting;
use App\Models\User;
use Livewire\Component;

class AdminHome extends Component
{
    public function render()
    {
        return view('pages.admin.home', [
            'activeUsers' => User::where('is_active', true)->count(),
            'activeDivisions' => Division::where('is_active', true)->count(),
            'activeHodAssignments' => DivisionHodAssignment::where('is_active', true)->count(),
            'hasActiveSettings' => ReportSetting::where('is_active', true)->where('is_active', true)->exists(),
        ])->layout('layouts.app');
    }
}
