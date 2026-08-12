<?php

namespace Database\Seeders;

use App\Models\Cotizacion;
use Illuminate\Database\Seeder;

class CotizacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // cotizaciones :: truncate ();
        $csvFile = fopen(base_path('database/data/cotiza_dolar.csv'), 'r');
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            // var_dump($data);
            if (! $firstline) {
                Cotizacion::create([
                    'id' => $data['0'],
                    'moneda_id' => 1,
                    'fecha' => $data['1'],
                    'cotizacion' => $data['2'],
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
