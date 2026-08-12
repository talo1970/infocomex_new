<?php

namespace Database\Seeders;

use App\Models\Provincia;
use Illuminate\Database\Seeder;

class ProvinciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provincias = [
            ['nombre' => 'Ciudad Autonoma de Bs. As.'],
            ['nombre' => 'Buenos Aires'],
            ['nombre' => 'Catamarca'],
            ['nombre' => 'Chaco'],
            ['nombre' => 'Chubut'],
            ['nombre' => 'Córdoba'],
            ['nombre' => 'Corrientes'],
            ['nombre' => 'Entre Ríos'],
            ['nombre' => 'Formosa'],
            ['nombre' => 'Jujuy'],
            ['nombre' => 'La Pampa'],
            ['nombre' => 'La Rioja'],
            ['nombre' => 'Mendoza'],
            ['nombre' => 'Misiones'],
            ['nombre' => 'Neuquén'],
            ['nombre' => 'Río Negro'],
            ['nombre' => 'Salta'],
            ['nombre' => 'San Juan'],
            ['nombre' => 'San Luis'],
            ['nombre' => 'Santa Cruz'],
            ['nombre' => 'Santa Fe'],
            ['nombre' => 'Santiago del Estero'],
            ['nombre' => 'Tierra del Fuego e Islas del Atlántico Sur'],
            ['nombre' => 'Tucumán'],
            ['nombre' => '-'],
        ];

        foreach ($provincias as $provincia) {
            Provincia::create(['nombre' => $provincia['nombre']]);
        }
    }
}
