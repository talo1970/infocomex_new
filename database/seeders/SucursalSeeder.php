<?php

namespace Database\Seeders;

use App\Models\Sucursal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SucursalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sucursal::create(['id' => 2, 'nombre' => 'CABA', 'direccion' => 'Frag Pte Sarmiento 451 Piso 6 Dpto C - CABA']);
        Sucursal::create(['id' => 5, 'nombre' => 'CHACRA', 'direccion' => 'El Chamical 19 - Villa Espil, Buenos Aires']);
        Sucursal::create(['id' => 6, 'nombre' => 'CABA Exportación', 'direccion' => 'Frag Pte Sarmiento 451 Piso 6 Dpto C - CABA']);
    }
}
