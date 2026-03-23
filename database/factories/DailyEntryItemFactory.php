<?php

namespace Database\Factories;

use App\Enums\WorkType;
use App\Models\BigRock;
use App\Models\DailyEntry;
use App\Models\DailyEntryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DailyEntryItem>
 */
class DailyEntryItemFactory extends Factory
{
    protected $model = DailyEntryItem::class;

    public function definition(): array
    {
        return [
            'daily_entry_id' => DailyEntry::factory(),
            'description' => fake()->sentence(8),
            'work_type' => fake()->randomElement(WorkType::cases())->value,
            'big_rock_id' => null,
            'planned_hours' => fake()->randomFloat(2, 1, 4),
            'realized_hours' => fake()->randomFloat(2, 1, 4),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function withBigRock(BigRock $bigRock): static
    {
        return $this->state(fn () => [
            'big_rock_id' => $bigRock->id,
        ]);
    }
}

