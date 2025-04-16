<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       // $this->call(PaymentIntentsTableSeeder::class);
        // \App\Models\User::factory(10)->create();
        User::updateOrCreate([
            'email' => 'admin@developer.com',
        ], [
            'name' => 'Admin',
            'password' => Hash::make('Pass@786'),
            'role' => 'admin',
        ]);
    }
}
