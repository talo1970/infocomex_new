<?php

namespace Database\Seeders;

use App\Models\FacturaMinuta;
use Illuminate\Database\Seeder;

class FacturaMinutaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // facturas :: truncate ();
        $csvFile = fopen(base_path('database/data/fac_boletos.csv'), 'r');
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            // var_dump($data);
            if (! $firstline) {
                FacturaMinuta::create([
                    'id' => $data['0'],
                    'factura_id' => $data['1'],
                    'minuta_id' => $data['5'],
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
