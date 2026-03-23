<?php

namespace App\Livewire\Admin;

use App\Models\ReportSetting;
use Livewire\Component;

class ReportSettings extends Component
{
    public string $plan_open_rule = '';

    public string $plan_close_rule = '';

    public string $realization_open_rule = '';

    public string $realization_close_rule = '';

    public ?ReportSetting $current = null;

    public bool $saved = false;

    public function mount(): void
    {
        $this->current = ReportSetting::query()->where('is_active', true)->latest('id')->first();

        if ($this->current) {
            $this->plan_open_rule = (string) $this->current->plan_open_rule;
            $this->plan_close_rule = (string) $this->current->plan_close_rule;
            $this->realization_open_rule = (string) $this->current->realization_open_rule;
            $this->realization_close_rule = (string) $this->current->realization_close_rule;
        }

        $this->saved = false;
    }

    public function save(): void
    {
        $data = $this->validate([
            'plan_open_rule' => ['required'],
            'plan_close_rule' => ['required'],
            'realization_open_rule' => ['required'],
            'realization_close_rule' => ['required'],
        ]);

        $payload = $data + ['timezone' => config('app.timezone')];

        if ($this->current) {
            $this->current->update($payload);
        } else {
            $this->current = ReportSetting::create($payload + ['is_active' => true]);
        }

        $this->saved = true;
    }

    public function render()
    {
        return view('pages.admin.report-settings', [
            'current' => $this->current,
        ])->layout('layouts.app');
    }
}
