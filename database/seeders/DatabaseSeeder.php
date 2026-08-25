<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin Principal',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        User::factory(10)->create();

        $this->call([
            ProductoSeeder::class,
            RepartidorSeeder::class,
            PagoSeeder::class,
        ]);
    }
}
