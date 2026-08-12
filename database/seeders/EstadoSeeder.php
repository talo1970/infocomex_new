<?php

namespace Database\Seeders;

use App\Models\Estado;
use Illuminate\Database\Seeder;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // cotizaciones :: truncate ();
        $csvFile = fopen(base_path('database/data/estados.csv'), 'r');
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            // var_dump($data);
            if (! $firstline) {
                Estado::create([
                    'id' => $data['0'],
                    'nombre' => $data['1'],
                    'producto_id' => $data['2'],
                    'cual' => $data['3'],
                    'tipo_usuario' => $data['4'],
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
