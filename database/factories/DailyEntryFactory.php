<?php

namespace Database\Factories;

use App\Enums\DailyPlanStatus;
use App\Enums\DailyRealizationStatus;
use App\Models\DailyEntry;
use App\Models\Division;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DailyEntry>
 */
class DailyEntryFactory extends Factory
{
    protected $model = DailyEntry::class;

    public function definition(): array
    {
        $user = User::factory()->create();

        return [
            'user_id' => $user->id,
            'division_id' => $user->division_id ?? Division::factory(),
            'entry_date' => now()->toDateString(),
            'plan_status' => DailyPlanStatus::Submitted,
            'realization_status' => DailyRealizationStatus::Submitted,
            'plan_submitted_at' => now()->subHours(2),
            'realization_submitted_at' => now()->subHour(),
        ];
    }
}

