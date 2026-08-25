<?php

namespace Database\Factories;

use App\Models\Pago;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pago>
 */
class PagoFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'completed', 'failed', 'refunded']);

        return [
            'user_id' => User::factory(),
            'amount' => fake()->randomFloat(2, 10, 2000),
            'payment_method' => fake()->randomElement(['cash', 'card', 'transfer']),
            'status' => $status,
            'description' => fake()->optional()->sentence(),
            'paid_at' => $status === 'completed' ? fake()->dateTimeThisMonth() : null,
        ];
    }
}
