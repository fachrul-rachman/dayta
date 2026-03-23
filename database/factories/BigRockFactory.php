<?php

namespace Database\Factories;

use App\Enums\BigRockStatus;
use App\Models\BigRock;
use App\Models\Division;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BigRock>
 */
class BigRockFactory extends Factory
{
    protected $model = BigRock::class;

    public function definition(): array
    {
        return [
            'division_id' => Division::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'status' => BigRockStatus::Active,
            'period_start' => now()->startOfMonth(),
            'period_end' => now()->endOfMonth(),
        ];
    }
}
