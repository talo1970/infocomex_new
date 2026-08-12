<?php

namespace Database\Seeders;

use App\Models\Clase;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Clase::create(['nombre' => 'Divisas', 'seleccion' => true]);
        Clase::create(['nombre' => 'Billetes', 'seleccion' => false]);
    }
}
