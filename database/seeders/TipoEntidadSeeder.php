<?php

namespace Database\Seeders;

use App\Models\TipoEntidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoEntidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoEntidad::create(['nombre' => 'Cliente', 'letra' => 'C', 'color' => 'red']);
        TipoEntidad::create(['nombre' => 'Banco', 'letra' => 'B', 'color' => 'green']);
    }
}
