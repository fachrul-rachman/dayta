<?php

namespace App\Livewire\Admin;

use App\Models\DiscordNotification;
use Livewire\Component;

class Notifications extends Component
{
    public ?string $status = null;

    public function updatedStatus(): void
    {
        // reset pagination if later converted to paginated list
    }

    public function render()
    {
        $query = DiscordNotification::query()
            ->orderByDesc('reporting_date')
            ->orderByDesc('created_at');

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $notifications = $query->limit(50)->get();

        return view('pages.admin.notifications', [
            'notifications' => $notifications,
        ])->layout('layouts.app');
    }
}

