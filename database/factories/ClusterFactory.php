<?php

namespace Database\Factories;

use App\Models\Content;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cluster>
 */
class ClusterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content_id' => Content::query()->inRandomOrder()->first()->id,
            'name' => fake()->title(),
            'description' => fake()->paragraph(3),
            'cover_id' => null //File::query()->inRandomOrder()->first()->id
        ];
    }
}
