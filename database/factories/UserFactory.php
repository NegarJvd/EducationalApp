<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender_index = array_rand(User::gender(), 1);
        $gender = User::gender()[$gender_index];

        return [
            'first_name' => fake()->firstName($gender),
            'last_name' => fake()->lastName(),
            'phone' => "0914" . rand(1111111, 9999999),
            'email' => fake()->unique()->safeEmail(),
            'birth_date' => fake()->date(),
            'gender' => $gender,
            'address' => fake()->address(),
            'landline_phone' => "041" . rand(11111111, 99999999),
            'father_name' => fake()->firstName('male'),
            'mother_name' => fake()->firstName('female'),
            'first_visit' => fake()->date(),
        ];
    }
}
