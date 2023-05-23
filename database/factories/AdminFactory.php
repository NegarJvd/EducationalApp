<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
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
        $gender_index = array_rand(Admin::gender(), 1);
        $gender = Admin::gender()[$gender_index];

        return [
            'first_name' => fake()->firstName($gender),
            'last_name' => fake()->lastName(),
            'phone' => "0914" . rand(1111111, 9999999), //just tabriz area code
            'email' => fake()->unique()->safeEmail(),
            'birth_date' => fake()->date(),
            'gender' => $gender,
            'address' => fake()->address(),
            'landline_phone' => "041" . rand(11111111, 99999999), //just tabriz area code
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
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
