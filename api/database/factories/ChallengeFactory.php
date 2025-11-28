<?php

namespace Database\Factories;

use App\Models\Challenge;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Challenge>
 */
class ChallengeFactory extends Factory
{
    protected $model = Challenge::class;

    public function definition(): array
    {
        return [
            'title' => 'Challenge: ' . $this->faker->sentence(2),
            'description' => $this->faker->sentence(12),
        ];
    }
}
