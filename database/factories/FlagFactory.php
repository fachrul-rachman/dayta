<?php

namespace Database\Factories;

use App\Enums\FlagSeverity;
use App\Models\Flag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Flag>
 */
class FlagFactory extends Factory
{
    protected $model = Flag::class;

    public function definition(): array
    {
        return [
            'scope_type' => 'division',
            'scope_id' => 1,
            'severity' => fake()->randomElement(FlagSeverity::cases())->value,
            'flagged_at' => now()->subDays(fake()->numberBetween(0, 7)),
            'title' => fake()->sentence(4),
            'details' => fake()->paragraph(),
        ];
    }
}

