<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::factory()
            ->assignRole("Manager")
            ->count(10)
            ->create();

        Admin::factory()
            ->assignRole("Therapist")
            ->count(10)
            ->create();
    }
}
