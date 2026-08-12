<?php

namespace Database\Seeders;

use App\Models\EntidadHonorarioProducto;
use Illuminate\Database\Seeder;

class EntidadHonorarioProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Entidad_honorarios_productos :: truncate ();
        $csvFile = fopen(base_path('database/data/entidad_honorario_productos.csv'), 'r');
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            // var_dump($data);
            if (! $firstline) {
                EntidadHonorarioProducto::create([
                    'entidad_id' => $data['1'],
                    'honorario_producto_id' => $data['2'],
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
