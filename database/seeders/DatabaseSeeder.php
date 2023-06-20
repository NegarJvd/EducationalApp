<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        //SuperAdmin---------------
        $role = Role::findOrCreate('SuperAdmin', 'web');
        $super_admin = Admin::create([
            'first_name' => "Negar",
            'last_name' => "Javadzadeh",
            'status' => Admin::status()[1],
            'phone' => "09149278078",
            'email' => "negarjavadzadeh2000@gmail.com",
            'medical_system_number' => rand(1111, 9999).'-'.fake()->randomLetter().'-'.fake()->randomLetter(),
            'birth_date' => "2000-09-16",
            'gender' => "female",
            'address' => "Iran - Tehran - Sharif university",
            'landline_phone' => "04134458603",
            'password' => Hash::make("9278078"),
            'field_of_profession' => fake()->word(),
            'resume' => fake()->paragraph(),
            'degree_of_education' => "ارشد"
        ]);
        $super_admin->assignRole([$role->id]);
        //--------------------------

        $this->call(AdminSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PermissionsSeeder::class);
    }
}
