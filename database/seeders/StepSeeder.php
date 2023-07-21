<?php

namespace Database\Seeders;

use App\Models\Cluster;
use App\Models\Step;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class StepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Cluster::all() as $cluster){
            $step_count = rand(3, 6);
            Step::factory()
                ->count($step_count)
                ->state(new Sequence(
                    fn ($sequence) => ['cluster_id' => $cluster->id, 'number' => $sequence->index + 1]
                ))
                ->create();

        }
    }
}
