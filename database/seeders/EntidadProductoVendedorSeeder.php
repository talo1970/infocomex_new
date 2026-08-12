<?php

namespace Database\Seeders;

use App\Models\EntidadProductoVendedor;
use Illuminate\Database\Seeder;

class EntidadProductoVendedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // honorario_productos :: truncate ();
        $csvFile = fopen(base_path('database/data/entidad_producto_vendedor.csv'), 'r');
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            // var_dump($data);
            if (! $firstline) {
                EntidadProductoVendedor::create([
                    'id' => $data['0'],
                    'entidad_id' => $data['2'],
                    'producto_id' => $data['4'],
                    'vendedor_id' => $data['3'],
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
