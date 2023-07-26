<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $is_user = fake()->randomElement([0, 1]);

        return [
            'user_id' => User::query()->inRandomOrder()->first()->id,
            'admin_id' => $is_user ? null : Admin::query()->inRandomOrder()->first()->id,
            'text' => fake()->sentence,
            'created_at' => fake()->dateTime()
        ];
    }
}
