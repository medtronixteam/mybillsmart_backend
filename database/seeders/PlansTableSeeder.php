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
            ['name' => 'free_trail','monthly_price' => 0,'annual_price' => 0, 'duration' => 'monthly'],
            ['name' => 'starter','monthly_price' => 1700,'annual_price' => 1700, 'duration' => 'monthly'],
            ['name' => 'pro', 'monthly_price' => 1700,'annual_price' => 1700, 'duration' => 'monthly'],
            ['name' => 'enterprise', 'monthly_price' => 1700,'annual_price' => 1700, 'duration' => 'monthly'],
            ['name' => 'growth_pack', 'monthly_price' => 1700,'annual_price' => 1700, 'duration' => 'monthly'],
            ['name' => 'scale_pack', 'monthly_price' => 1700,'annual_price' => 1700, 'duration' => 'monthly'],
            ['name' => 'max_pack', 'monthly_price' => 1700,'annual_price' => 1700, 'duration' => 'monthly'],
            ['name' => 'volume_mini', 'monthly_price' => 1700,'annual_price' => 1700, 'duration' => 'monthly'],
            ['name' => 'volume_medium', 'monthly_price' => 1700,'annual_price' => 1700, 'duration' => 'monthly'],
            ['name' => 'volume_max', 'monthly_price' => 1700,'annual_price' => 1700, 'duration' => 'monthly'],
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
