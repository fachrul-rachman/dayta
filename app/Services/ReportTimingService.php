<?php

namespace App\Services;

use App\Models\ReportSetting;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

class ReportTimingService
{
    public function currentSettings(): ?ReportSetting
    {
        return ReportSetting::query()
            ->where('is_active', true)
            ->latest('id')
            ->first();
    }

    public function now(): CarbonImmutable
    {
        return CarbonImmutable::now(config('app.timezone'));
    }

    public function isWithinWindow(?string $openRule, ?string $closeRule, ?CarbonImmutable $now = null): bool
    {
        $now ??= $this->now();

        if (! $openRule || ! $closeRule) {
            return false;
        }

        $open = $this->atTime($now, $openRule);
        $close = $this->atTime($now, $closeRule);

        if (! $open || ! $close) {
            return false;
        }

        return $now->betweenIncluded($open, $close);
    }

    public function windowState(?string $openRule, ?string $closeRule, ?CarbonImmutable $now = null): string
    {
        $now ??= $this->now();

        if (! $openRule || ! $closeRule) {
            return 'locked';
        }

        $open = $this->atTime($now, $openRule);
        $close = $this->atTime($now, $closeRule);

        if (! $open || ! $close) {
            return 'locked';
        }

        if ($now->lt($open)) {
            return 'locked';
        }

        if ($now->gt($close)) {
            return 'closed';
        }

        return 'open';
    }

    private function atTime(CarbonInterface $base, string $time): ?CarbonImmutable
    {
        if (! preg_match('/^\d{2}:\d{2}/', $time)) {
            return null;
        }

        return CarbonImmutable::parse($base->toDateString().' '.$time, $base->getTimezone());
    }
}
