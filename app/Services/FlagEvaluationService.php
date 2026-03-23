<?php

namespace App\Services;

use App\Enums\DailyPlanStatus;
use App\Enums\DailyRealizationStatus;
use App\Enums\FlagSeverity;
use App\Enums\FlagType;
use App\Enums\UserRole;
use App\Enums\WorkType;
use App\Models\DailyEntry;
use App\Models\DailyEntryItem;
use App\Models\Flag;
use App\Models\ReportSetting;
use App\Models\User;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class FlagEvaluationService
{
    public function __construct(
        protected ReportTimingService $timing,
    ) {}

    public function evaluateForDay(CarbonImmutable $day): void
    {
        $settings = $this->timing->currentSettings();

        if (! $settings) {
            return;
        }

        $reporters = User::query()
            ->where('is_active', true)
            ->whereIn('role', [UserRole::Manager, UserRole::Hod])
            ->get();

        foreach ($reporters as $user) {
            $entry = DailyEntry::query()
                ->where('user_id', $user->id)
                ->whereDate('entry_date', $day->toDateString())
                ->first();

            $this->evaluateMissingSubmission($user, $entry, $settings, $day);
            $this->evaluateLateSubmission($user, $entry, $settings, $day);
            $this->evaluateOperationalDominance($user, $entry, $day);
            $this->evaluateRepetitiveInput($user, $entry, $day);
        }
    }

    public function evaluateForEntry(DailyEntry $entry): void
    {
        $user = $entry->user;
        $day = CarbonImmutable::parse($entry->entry_date, config('app.timezone'));

        $this->evaluateOperationalDominance($user, $entry, $day);
        $this->evaluateRepetitiveInput($user, $entry, $day);
    }

    protected function evaluateMissingSubmission(User $user, ?DailyEntry $entry, ReportSetting $settings, CarbonImmutable $day): void
    {
        $planRulesValid = $this->hasValidRules($settings->plan_open_rule, $settings->plan_close_rule);
        $realRulesValid = $this->hasValidRules($settings->realization_open_rule, $settings->realization_close_rule);

        if (! $planRulesValid && ! $realRulesValid) {
            return;
        }

        $planCloseAt = $planRulesValid ? $this->atTimeForDate($day, $settings->plan_close_rule) : null;
        $realCloseAt = $realRulesValid ? $this->atTimeForDate($day, $settings->realization_close_rule) : null;

        $evaluationMoment = $day->endOfDay();

        $missingPlan = false;
        $missingReal = false;

        if ($planRulesValid && $evaluationMoment->gt($planCloseAt)) {
            if (! $entry || $entry->plan_status !== DailyPlanStatus::Submitted) {
                $missingPlan = true;
            }
        }

        if ($realRulesValid && $evaluationMoment->gt($realCloseAt)) {
            if (! $entry || $entry->realization_status !== DailyRealizationStatus::Submitted) {
                $missingReal = true;
            }
        }

        if (! $missingPlan && ! $missingReal) {
            return;
        }

        $parts = [];
        if ($missingPlan) {
            $parts[] = 'plan';
        }
        if ($missingReal) {
            $parts[] = 'realization';
        }

        $modeText = implode(' and ', $parts);

        $title = 'Missing daily '.$modeText.' submission';
        $details = sprintf(
            'User %s has no submitted %s for %s. Plan cutoff: %s, realization cutoff: %s.',
            $user->name,
            $modeText,
            $day->toDateString(),
            $planCloseAt?->format('H:i') ?? '-',
            $realCloseAt?->format('H:i') ?? '-',
        );

        $this->upsertRuleFlag(
            $user,
            $day,
            FlagType::MissingSubmission,
            FlagSeverity::High,
            $title,
            $details,
        );
    }

    protected function evaluateLateSubmission(User $user, ?DailyEntry $entry, ReportSetting $settings, CarbonImmutable $day): void
    {
        if (! $entry) {
            return;
        }

        $planRulesValid = $this->hasValidRules($settings->plan_open_rule, $settings->plan_close_rule);
        $realRulesValid = $this->hasValidRules($settings->realization_open_rule, $settings->realization_close_rule);

        if (! $planRulesValid && ! $realRulesValid) {
            return;
        }

        $planCloseAt = $planRulesValid ? $this->atTimeForDate($day, $settings->plan_close_rule) : null;
        $realCloseAt = $realRulesValid ? $this->atTimeForDate($day, $settings->realization_close_rule) : null;

        $latePlan = false;
        $lateReal = false;

        if ($planRulesValid && $entry->plan_status === DailyPlanStatus::Submitted && $entry->plan_submitted_at && $planCloseAt && $entry->plan_submitted_at->gt($planCloseAt)) {
            $latePlan = true;
        }

        if ($realRulesValid && $entry->realization_status === DailyRealizationStatus::Submitted && $entry->realization_submitted_at && $realCloseAt && $entry->realization_submitted_at->gt($realCloseAt)) {
            $lateReal = true;
        }

        if (! $latePlan && ! $lateReal) {
            return;
        }

        $parts = [];
        if ($latePlan) {
            $parts[] = 'plan';
        }
        if ($lateReal) {
            $parts[] = 'realization';
        }

        $modeText = implode(' and ', $parts);

        $title = 'Late '.$modeText.' submission';
        $details = sprintf(
            'User %s submitted %s late on %s. Plan submitted at: %s (cutoff %s), realization submitted at: %s (cutoff %s).',
            $user->name,
            $modeText,
            $day->toDateString(),
            optional($entry->plan_submitted_at)->format('Y-m-d H:i') ?? '-',
            $planCloseAt?->format('H:i') ?? '-',
            optional($entry->realization_submitted_at)->format('Y-m-d H:i') ?? '-',
            $realCloseAt?->format('H:i') ?? '-',
        );

        $this->upsertRuleFlag(
            $user,
            $day,
            FlagType::LateSubmission,
            FlagSeverity::Medium,
            $title,
            $details,
        );
    }

    protected function evaluateOperationalDominance(User $user, ?DailyEntry $entry, CarbonImmutable $day): void
    {
        if (! $entry) {
            return;
        }

        $items = $entry->items()->get();

        $totalItems = $items->where(fn (DailyEntryItem $item) => trim($item->description) !== '')->count();

        if ($totalItems < 4) {
            return;
        }

        $bigRockItems = $items->where('work_type', WorkType::BigRock)->count();
        $operationalItems = $items->whereIn('work_type', [WorkType::Operational, WorkType::AdHoc])->count();

        if ($totalItems === 0) {
            return;
        }

        $operationalShare = $operationalItems / $totalItems;

        if (! ($operationalShare >= 0.7 && $bigRockItems <= 1)) {
            return;
        }

        $title = 'Operational workload dominates daily report';
        $details = sprintf(
            'On %s, %d of %d items are Operational/Ad Hoc, with %d Big Rock items.',
            $day->toDateString(),
            $operationalItems,
            $totalItems,
            $bigRockItems,
        );

        $this->upsertRuleFlag(
            $user,
            $day,
            FlagType::OperationalDominance,
            FlagSeverity::Medium,
            $title,
            $details,
        );
    }

    protected function evaluateRepetitiveInput(User $user, ?DailyEntry $entry, CarbonImmutable $day): void
    {
        if (! $entry) {
            return;
        }

        $todayItems = $entry->items()->get();

        $planToday = $todayItems->filter(fn (DailyEntryItem $item) => $item->planned_hours !== null);
        $realToday = $todayItems->filter(fn (DailyEntryItem $item) => $item->realized_hours !== null);

        [$planHistory, $realHistory] = $this->loadHistoryItems($user, $day);

        $issues = [];

        if ($this->hasRepetitivePattern($planToday, $planHistory)) {
            $issues[] = 'plan';
        }

        if ($this->hasRepetitivePattern($realToday, $realHistory)) {
            $issues[] = 'realization';
        }

        if (empty($issues)) {
            return;
        }

        $modeText = implode(' and ', $issues);

        $title = 'Repetitive reporting pattern detected';
        $details = sprintf(
            'On %s, user %s shows highly repetitive %s content compared to their last reporting days.',
            $day->toDateString(),
            $user->name,
            $modeText,
        );

        $this->upsertRuleFlag(
            $user,
            $day,
            FlagType::RepetitiveInput,
            FlagSeverity::Medium,
            $title,
            $details,
        );
    }

    protected function loadHistoryItems(User $user, CarbonImmutable $day): array
    {
        $entries = DailyEntry::query()
            ->where('user_id', $user->id)
            ->whereDate('entry_date', '<', $day->toDateString())
            ->orderByDesc('entry_date')
            ->limit(5)
            ->get();

        $planHistory = collect();
        $realHistory = collect();

        foreach ($entries as $entry) {
            $items = $entry->items()->get();
            $planHistory = $planHistory->merge($items->filter(fn (DailyEntryItem $item) => $item->planned_hours !== null));
            $realHistory = $realHistory->merge($items->filter(fn (DailyEntryItem $item) => $item->realized_hours !== null));
        }

        return [$planHistory, $realHistory];
    }

    protected function hasRepetitivePattern(Collection $todayItems, Collection $historyItems): bool
    {
        $normalize = function (string $text): ?string {
            $text = trim(mb_strtolower($text));
            $text = preg_replace('/\s+/', ' ', $text ?? '');
            $text = preg_replace('/[[:punct:]]+/', '', $text ?? '');

            if (! $text || mb_strlen($text) < 30) {
                return null;
            }

            return $text;
        };

        $todayNormalized = [];

        foreach ($todayItems as $item) {
            $norm = $normalize($item->description);
            if ($norm !== null) {
                $todayNormalized[] = $norm;
            }
        }

        if (count($todayNormalized) < 3) {
            return false;
        }

        $historyByText = [];

        foreach ($historyItems as $item) {
            $norm = $normalize($item->description);
            if ($norm === null) {
                continue;
            }

            $historyByText[$norm] = ($historyByText[$norm] ?? 0) + 1;
        }

        if (empty($historyByText)) {
            return false;
        }

        $stronglyRepeated = 0;

        foreach ($todayNormalized as $text) {
            if (($historyByText[$text] ?? 0) >= 2) {
                $stronglyRepeated++;
            }
        }

        if ($stronglyRepeated >= 3) {
            return true;
        }

        $share = $stronglyRepeated / count($todayNormalized);

        return $share >= 0.7;
    }

    protected function hasValidRules(?string $openRule, ?string $closeRule): bool
    {
        return ! empty($openRule) && ! empty($closeRule);
    }

    protected function atTimeForDate(CarbonInterface $date, ?string $time): ?CarbonImmutable
    {
        if (! $time) {
            return null;
        }

        return CarbonImmutable::parse($date->toDateString().' '.$time, $date->getTimezone());
    }

    protected function upsertRuleFlag(
        User $user,
        CarbonImmutable $day,
        FlagType $type,
        FlagSeverity $severity,
        string $title,
        ?string $details,
    ): void {
        $existing = Flag::query()
            ->where('scope_type', 'user')
            ->where('scope_id', $user->id)
            ->where('type', $type)
            ->whereDate('flagged_at', $day->toDateString())
            ->first();

        $data = [
            'scope_type' => 'user',
            'scope_id' => $user->id,
            'type' => $type,
            'severity' => $severity,
            'flagged_at' => $day->endOfDay(),
            'title' => $title,
            'details' => $details,
        ];

        if ($existing) {
            $existing->update($data);
        } else {
            Flag::create($data);
        }
    }
}
