<?php

namespace App\Services;

use App\Enums\FlagSeverity;
use App\Enums\FlagType;
use App\Models\DiscordNotification;
use App\Models\Flag;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;

class DiscordNotificationService
{
    public function sendDailyAlert(CarbonImmutable $day): void
    {
        $config = config('discord');

        if (! ($config['enabled'] ?? false) || empty($config['webhook_url'])) {
            return;
        }

        $date = $day->toDateString();

        $alreadySent = DiscordNotification::query()
            ->whereDate('reporting_date', $date)
            ->where('status', 'sent')
            ->exists();

        if ($alreadySent) {
            return;
        }

        [$message, $meta] = $this->buildMessage($day) ?? [null, null];

        if (! $message) {
            return;
        }

        $log = DiscordNotification::create([
            'reporting_date' => $date,
            'status' => 'pending',
            'channel' => 'webhook',
            'message' => $message,
            'divisions_count' => $meta['divisions'] ?? 0,
            'people_count' => $meta['people'] ?? 0,
            'findings_count' => $meta['findings'] ?? 0,
            'attempt_count' => 0,
        ]);

        $maxAttempts = 3;
        $lastError = null;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $log->attempt_count = $attempt;
            $log->save();

            try {
                $response = Http::timeout($config['timeout'] ?? 5)
                    ->post($config['webhook_url'], [
                        'content' => $message,
                    ]);

                if ($response->successful()) {
                    $log->status = 'sent';
                    $log->sent_at = now();
                    $log->error_message = null;
                    $log->save();

                    return;
                }

                $lastError = 'HTTP '.$response->status();
            } catch (\Throwable $e) {
                $lastError = $e->getMessage();
            }
        }

        $log->status = 'failed';
        $log->failed_at = now();
        $log->error_message = $lastError ? mb_substr($lastError, 0, 1000) : null;
        $log->save();
    }

    private function buildMessage(CarbonImmutable $day): ?array
    {
        $date = $day->toDateString();

        $flags = Flag::query()
            ->where('scope_type', 'user')
            ->whereNotNull('type')
            ->whereIn('severity', [FlagSeverity::Medium, FlagSeverity::High])
            ->whereIn('type', [
                FlagType::MissingSubmission,
                FlagType::LateSubmission,
                FlagType::OperationalDominance,
                FlagType::RepetitiveInput,
            ])
            ->whereDate('flagged_at', $date)
            ->get();

        if ($flags->isEmpty()) {
            return null;
        }

        $userIds = $flags->pluck('scope_id')->unique()->all();

        $users = User::query()
            ->with('division')
            ->whereIn('id', $userIds)
            ->get()
            ->keyBy('id');

        $divisions = [];
        $totalFindings = 0;

        foreach ($flags as $flag) {
            $user = $users->get($flag->scope_id);

            if (! $user) {
                continue;
            }

            $divisionName = $user->division?->name ?? 'Unassigned';
            $divisionKey = ($user->division_id ?? 0).'|'.$divisionName;

            if (! isset($divisions[$divisionKey])) {
                $divisions[$divisionKey] = [
                    'name' => $divisionName,
                    'users' => [],
                    'highest_severity' => 0,
                    'findings_count' => 0,
                ];
            }

            $severityScore = $flag->severity === FlagSeverity::High ? 2 : 1;
            $divisions[$divisionKey]['highest_severity'] = max($divisions[$divisionKey]['highest_severity'], $severityScore);
            $divisions[$divisionKey]['findings_count']++;
            $totalFindings++;

            $userKey = $user->id;

            if (! isset($divisions[$divisionKey]['users'][$userKey])) {
                $divisions[$divisionKey]['users'][$userKey] = [
                    'name' => $user->name,
                    'findings' => [],
                    'highest_severity' => 0,
                    'findings_count' => 0,
                ];
            }

            $divisions[$divisionKey]['users'][$userKey]['highest_severity'] = max(
                $divisions[$divisionKey]['users'][$userKey]['highest_severity'],
                $severityScore,
            );
            $divisions[$divisionKey]['users'][$userKey]['findings_count']++;

            $label = $this->labelForType($flag->type);

            if ($label && ! in_array($label, $divisions[$divisionKey]['users'][$userKey]['findings'], true)) {
                $divisions[$divisionKey]['users'][$userKey]['findings'][] = $label;
            }
        }

        if (empty($divisions)) {
            return null;
        }

        // Sort divisions
        usort($divisions, function (array $a, array $b): int {
            if ($a['highest_severity'] !== $b['highest_severity']) {
                return $b['highest_severity'] <=> $a['highest_severity'];
            }

            if ($a['findings_count'] !== $b['findings_count']) {
                return $b['findings_count'] <=> $a['findings_count'];
            }

            return strcmp($a['name'], $b['name']);
        });

        $totalDivisions = count($divisions);
        $totalPeople = 0;

        $lines = [];

        $lines[] = 'Daily Reporting Alert — '.$day->format('d M Y');

        foreach ($divisions as &$division) {
            $users = array_values($division['users']);
            $totalPeople += count($users);

            usort($users, function (array $a, array $b): int {
                if ($a['highest_severity'] !== $b['highest_severity']) {
                    return $b['highest_severity'] <=> $a['highest_severity'];
                }

                if ($a['findings_count'] !== $b['findings_count']) {
                    return $b['findings_count'] <=> $a['findings_count'];
                }

                return strcmp($a['name'], $b['name']);
            });

            $division['users_sorted'] = $users;
        }

        $lines[] = sprintf(
            '%d divisions • %d people • %d findings',
            $totalDivisions,
            $totalPeople,
            $totalFindings,
        );

        $lines[] = '';

        foreach ($divisions as $division) {
            $lines[] = $division['name'];

            foreach ($division['users_sorted'] as $user) {
                $lines[] = '- '.$user['name'].' – '.implode(', ', $user['findings']);
            }

            $lines[] = '';
        }

        $message = trim(implode("\n", $lines));

        return [$message, [
            'divisions' => $totalDivisions,
            'people' => $totalPeople,
            'findings' => $totalFindings,
        ]];
    }

    private function labelForType(?FlagType $type): ?string
    {
        return match ($type) {
            FlagType::MissingSubmission => 'missing submission',
            FlagType::LateSubmission => 'late submission',
            FlagType::OperationalDominance => 'operational dominance',
            FlagType::RepetitiveInput => 'repetitive input',
            default => null,
        };
    }
}
