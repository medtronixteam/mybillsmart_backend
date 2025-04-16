<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            ['name' => 'starter', 'price' => 99, 'duration' => 'monthly'],
            ['name' => 'pro', 'price' => 450, 'duration' => 'monthly'],
            ['name' => 'enterprise', 'price' => 1890, 'duration' => 'monthly'],
            ['name' => 'growth_pack', 'price' => 420, 'duration' => 'monthly'],
            ['name' => 'scale_pack', 'price' => 790, 'duration' => 'monthly'],
            ['name' => 'max_pack', 'price' => 1700, 'duration' => 'monthly'],
        ];

        // Loop through each plan and use updateOrCreate
        foreach ($plans as $planData) {
            Plan::updateOrCreate(
                ['name' => $planData['name']], // Search by name
                $planData // Update or create with these attributes
            );
        }
    }
}
