<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\AiSummary;
use App\Models\DailyEntry;
use App\Models\Flag;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class AiSummaryService
{
    public function generate(array $payload, User $user): AiSummary
    {
        $config = config('ai.summary');

        if (! ($config['enabled'] ?? false) || empty($config['api_key'])) {
            return AiSummary::create([
                'scope_type' => $payload['scope_type'],
                'scope_id' => $payload['scope_id'] ?? null,
                'date_from' => $payload['date_from'] ?? null,
                'date_to' => $payload['date_to'] ?? null,
                'summary' => null,
                'filters' => Arr::get($payload, 'filters', []),
                'generated_by_user_id' => $user->id,
            ]);
        }

        $baseUrl = rtrim($config['base_url'] ?: 'https://api.openai.com', '/');
        $model = $config['model'] ?: 'gpt-4.1-mini';

        $scope = $payload['scope_type'] ?? 'division';
        $dateFrom = $payload['date_from'] ?? null;
        $dateTo = $payload['date_to'] ?? null;

        $rangeText = $dateFrom && $dateTo
            ? "from {$dateFrom} to {$dateTo}"
            : 'for the selected period';

        $context = $this->buildContext($payload, $user);

        $prompt = "Kamu membantu pimpinan meninjau data pelaporan terstruktur.\n"
            ."Gunakan HANYA konteks terstruktur di bawah ini. Jangan mengada-ada user, masalah, atau angka yang tidak ada di konteks.\n"
            ."Context (JSON):\n"
            .json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            ."\n\n"
            ."Tulis ringkasan dalam bahasa Indonesia dengan struktur persis seperti ini:\n"
            ."1) Tanggal: satu baris yang menjelaskan periode ({$rangeText}).\n"
            ."2) Ringkasan divisi: 2-3 kalimat yang menjelaskan kondisi dan pola utama, berdasarkan konteks.\n"
            ."3) Masalah: daftar poin singkat. Setiap poin, jika memungkinkan, menyebutkan user atau tim tertentu yang ada di konteks dan menjelaskan masalah konkretnya (misalnya belum mengisi laporan, sering muncul di flag, dan sebagainya). Jika tidak ada masalah yang jelas, sebutkan itu secara eksplisit.\n"
            ."4) Langkah berikutnya: 2-3 poin tindakan praktis yang bisa diambil HoD atau Director, tetap hanya berdasarkan konteks.\n"
            ."Jangan lebih dari 12 baris total. Jangan membuat angka detail yang tidak tersurat dari konteks.";

        $text = null;

        try {
            $response = Http::withToken($config['api_key'])
                ->baseUrl($baseUrl)
                ->post('/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a concise reporting assistant for an internal business dashboard.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'temperature' => 0.2,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $text = Arr::get($data, 'choices.0.message.content') ?: null;
            }
        } catch (\Throwable $e) {
            // Swallow integration errors and fall back to a generic message.
        }

        if (! $text) {
            $text = 'AI summary is currently enabled, but a detailed response could not be generated from the provider.';
        }

        return AiSummary::create([
            'scope_type' => $payload['scope_type'],
            'scope_id' => $payload['scope_id'] ?? null,
            'date_from' => $payload['date_from'] ?? null,
            'date_to' => $payload['date_to'] ?? null,
            'summary' => $text,
            'filters' => Arr::get($payload, 'filters', []),
            'generated_by_user_id' => $user->id,
        ]);
    }

    protected function buildContext(array $payload, User $requestingUser): array
    {
        $scope = $payload['scope_type'] ?? 'division';
        $scopeId = $payload['scope_id'] ?? null;
        $dateFrom = $payload['date_from'] ?? null;
        $dateTo = $payload['date_to'] ?? null;

        $divisionId = null;

        if ($scope === 'division') {
            $divisionId = $scopeId ?: $requestingUser->division_id;
        }

        $from = $dateFrom ? date('Y-m-d', strtotime($dateFrom)) : null;
        $to = $dateTo ? date('Y-m-d', strtotime($dateTo)) : null;

        $entriesQuery = DailyEntry::query();

        if ($divisionId) {
            $entriesQuery->where('division_id', $divisionId);
        }

        if ($from) {
            $entriesQuery->whereDate('entry_date', '>=', $from);
        }

        if ($to) {
            $entriesQuery->whereDate('entry_date', '<=', $to);
        }

        $entries = $entriesQuery->with('user')->get();

        $reportersQuery = User::query();

        if ($divisionId) {
            $reportersQuery->where('division_id', $divisionId);
        }

        $reportersQuery->whereIn('role', [UserRole::Manager, UserRole::Hod]);

        $reporters = $reportersQuery->get();

        $perUser = [];

        foreach ($reporters as $reporter) {
            $userEntries = $entries->where('user_id', $reporter->id);

            $perUser[] = [
                'id' => $reporter->id,
                'name' => $reporter->name,
                'role' => $reporter->role->value ?? null,
                'total_entries' => $userEntries->count(),
                'submitted_plan_days' => $userEntries->where('plan_status', 'submitted')->count(),
                'submitted_realization_days' => $userEntries->where('realization_status', 'submitted')->count(),
            ];
        }

        $flagsQuery = Flag::query();

        if ($divisionId) {
            $flagsQuery->where('scope_type', 'division')->where('scope_id', $divisionId);
        }

        if ($from) {
            $flagsQuery->whereDate('flagged_at', '>=', $from);
        }

        if ($to) {
            $flagsQuery->whereDate('flagged_at', '<=', $to);
        }

        $flags = $flagsQuery->get()->map(function (Flag $flag) {
            return [
                'severity' => $flag->severity?->value,
                'flagged_at' => optional($flag->flagged_at)->toDateString(),
                'title' => $flag->title,
                'details' => $flag->details,
            ];
        })->values()->all();

        $flagsBySeverity = [
            'low' => 0,
            'medium' => 0,
            'high' => 0,
        ];

        foreach ($flags as $flag) {
            if ($flag['severity'] && isset($flagsBySeverity[$flag['severity']])) {
                $flagsBySeverity[$flag['severity']]++;
            }
        }

        return [
            'scope_type' => $scope,
            'scope_id' => $scopeId,
            'division_id' => $divisionId,
            'date_from' => $from,
            'date_to' => $to,
            'total_entries' => $entries->count(),
            'reporters' => $perUser,
            'flags' => $flags,
            'flags_by_severity' => $flagsBySeverity,
        ];
    }
}
