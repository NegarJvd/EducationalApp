<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->count(10)
            ->create();

        $assigned_users = User::query()
                            ->inRandomOrder()
                            ->limit(6)
                            ->get();

        foreach ($assigned_users as $user){
            $user->admin_id = Admin::query()
                ->inRandomOrder()
                ->first()
                ->id;
            $user->save();
        }
    }
}
