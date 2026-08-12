<?php

namespace Database\Seeders;

use App\Models\CodigoVenta;
use Illuminate\Database\Seeder;

class CodigoVentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $codigos_venta = [
            ['nombre' => 'FOB', 'seleccion' => '1'],
            ['nombre' => 'CIF', 'seleccion' => '0'],
            ['nombre' => 'C y F', 'seleccion' => '0'],
            ['nombre' => 'DDU', 'seleccion' => '0'],
            ['nombre' => 'DDP', 'seleccion' => '0'],
            ['nombre' => 'FLETE', 'seleccion' => '0'],
            ['nombre' => 'SEGURO', 'seleccion' => '0'],
            ['nombre' => 'CFR', 'seleccion' => '0'],
            ['nombre' => 'EXW', 'seleccion' => '0'],
            ['nombre' => 'CPT', 'seleccion' => '0'],
            ['nombre' => 'FCA', 'seleccion' => '0'],
            ['nombre' => 'CIP', 'seleccion' => '0'],
            ['nombre' => 'FLETE y SEGURO', 'seleccion' => '0'],
        ];

        foreach ($codigos_venta as $codigo) {
            CodigoVenta::create([
                'nombre' => $codigo['nombre'],
                'seleccion' => $codigo['seleccion'],
            ]);
        }
    }
}
