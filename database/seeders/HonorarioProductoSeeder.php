<?php

namespace Database\Seeders;

use App\Models\HonorarioProducto;
use Illuminate\Database\Seeder;

class HonorarioProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // honorario_productos :: truncate ();
        $csvFile = fopen(base_path('database/data/productos_honorarios.csv'), 'r');
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            // var_dump($data);
            if (! $firstline) {
                HonorarioProducto::create([
                    'honorario_id' => $data['1'],
                    'producto_id' => $data['0'],
                    'importe' => $data['2'],
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
