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
            ['name' => 'free_trial','monthly_price' => 0,'annual_price' => 0, 'duration' => 'monthly','invoices_per_month'=>10,'agents_per_month'=>0],
            ['name' => 'starter','monthly_price' => 99,'annual_price' => 990, 'duration' => 'monthly','invoices_per_month'=>200,'agents_per_month'=>1],
            ['name' => 'pro', 'monthly_price' => 450,'annual_price' => 4500, 'duration' => 'monthly','invoices_per_month'=>1000,'agents_per_month'=>5],
            ['name' => 'enterprise', 'monthly_price' => 1890,'annual_price' => 18900, 'duration' => 'monthly','invoices_per_month'=>5000,'agents_per_month'=>25],
            ['name' => 'growth_pack', 'monthly_price' => 420,'annual_price' => 4200, 'duration' => 'monthly','agents_per_month'=>100],
            ['name' => 'scale_pack', 'monthly_price' => 790,'annual_price' => 7900, 'duration' => 'monthly','agents_per_month'=>10],
            ['name' => 'max_pack', 'monthly_price' => 1700,'annual_price' => 17000, 'duration' => 'monthly','agents_per_month'=>25],
            ['name' => 'volume_mini', 'monthly_price' => 49,'annual_price' => 4990, 'duration' => 'monthly','invoices_per_month'=>500],
            ['name' => 'volume_medium', 'monthly_price' => 129,'annual_price' => 1290, 'duration' => 'monthly','invoices_per_month'=>1500],
            ['name' => 'volume_max', 'monthly_price' =>239,'annual_price' => 2390, 'duration' => 'monthly','invoices_per_month'=>4000],
        ];


        foreach ($plans as $planData) {
            Plan::updateOrCreate(
                ['name' => $planData['name']], // Search by name
                $planData // Update or create with these attributes
            );
        }
    }
}
