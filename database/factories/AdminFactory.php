<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status_index = array_rand(Admin::status(), 1);
        $status = Admin::status()[$status_index];

        $gender_index = array_rand(Admin::gender(), 1);
        $gender = Admin::gender()[$gender_index];

        $degree_of_education_index = array_rand(Admin::degree_of_education(), 1);
        $degree_of_education = Admin::degree_of_education()[$degree_of_education_index];

        return [
            'first_name' => fake()->firstName($gender),
            'last_name' => fake()->lastName(),
            'status' => $status,
            'phone' => "0914" . rand(1111111, 9999999), //just tabriz area code
            'email' => fake()->unique()->safeEmail(),
            'medical_system_number' => rand(1111, 9999).'-'.fake()->randomLetter().'-'.fake()->randomLetter(),
            'birth_date' => fake()->date(),
            'gender' => $gender,
            'address' => fake()->address(),
            'landline_phone' => "041" . rand(11111111, 99999999), //just tabriz area code
            'password' => Hash::make("password"),
            'field_of_profession' => fake()->word(),
            'resume' => fake()->paragraph(),
            'degree_of_education' => $degree_of_education
        ];
    }

    public function assignRole($role_name)
    {
        return $this->afterCreating(function (Admin $admin) use($role_name) {
            $role = Role::findOrCreate($role_name, 'web');
            $admin->assignRole([$role->id]);
        });
    }

}
