<?php

namespace Database\Seeders;

use App\Models\Contacto;
use Illuminate\Database\Seeder;

class ContactoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // entidades :: truncate ();
        $csvFile = fopen(base_path('database/data/contactos.csv'), 'r');
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            // var_dump($data);
            if (! $firstline) {
                Contacto::create([
                    'entidad_id' => $data['0'],
                    'contacto' => $data['3'],
                    'mail' => $data['4'],
                    'telefono' => $data['5'],
                    'domicilio' => $data['6'],
                    'numero' => $data['7'],
                    'departamento_piso' => $data['8'],
                    'codigo_postal' => $data['9'],
                    'localidad' => $data['10'],
                    'provincia_id' => $data['11'],
                    'pais' => $data['12'],
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
