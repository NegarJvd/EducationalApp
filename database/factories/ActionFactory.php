<?php

namespace Database\Factories;

use App\Models\Step;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Action>
 */
class ActionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::query()->inRandomOrder()->first()->id,
            'step_id' => Step::query()->inRandomOrder()->first()->id,
            'count' => rand(1, 10),
            'result' => rand(0, 2),
            'created_at' => fake()->dateTime()
        ];
    }
}
