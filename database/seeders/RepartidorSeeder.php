<?php

namespace Database\Seeders;

use App\Models\Repartidor;

Repartidor::create([
    'name' => 'Carlos López',
    'email' => 'carlos@example.com',
    'phone' => '3001234567',
    'vehicle_type' => 'Moto',
    'license_plate' => 'ABC123',
    'status' => 'disponible',
]);

// O si usas Factory:
Repartidor::factory(10)->create();
