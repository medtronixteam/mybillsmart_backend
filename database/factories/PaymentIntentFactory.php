<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentIntent>
 */
class PaymentIntentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'stripe_payment_intent_id' => Str::uuid(),
            'currency' => 'usd',
            'amount' => $this->faker->randomFloat(2, 10, 500),
            'status' => $this->faker->randomElement(['pending', 'succeeded', 'failed']),
            'plan_name' => $this->faker->randomElement(['Starter Plan', 'Pro Plan', 'Enterprise Plan']),
            'user_id' => \App\Models\User::factory(), // create a related user
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
