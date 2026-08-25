<?php

namespace Database\Factories;

use App\Models\Repartidor;
use Illuminate\Database\Eloquent\Factories\Factory;
class RepartidorFactory extends Factory
{
    protected $model = Repartidor::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'vehicle_type' => $this->faker->randomElement(['Moto', 'Bicicleta', 'Carro']),
            'license_plate' => strtoupper($this->faker->bothify('???###')),
            'status' => 'disponible',
        ];
    }
}
