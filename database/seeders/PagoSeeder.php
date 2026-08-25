<?php

namespace Database\Seeders;

use App\Models\Pago;
use Illuminate\Database\Seeder;

class PagoSeeder extends Seeder
{
    public function run(): void
    {
        Pago::factory(30)->create();
    }
}
