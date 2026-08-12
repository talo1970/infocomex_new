<?php

namespace Database\Seeders;

use App\Models\Recibo;
use Illuminate\Database\Seeder;

class ReciboSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // recibos :: truncate ();
        $csvFile = fopen(base_path('database/data/recibo.csv'), 'r');
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            // var_dump($data);
            if (! $firstline) {

                $re_afip = ($data['17'] == '' ? null : ($data['17'] == 'N' ? 0 : 1));
                $re_dgr = ($data['18'] == '' ? null : ($data['18'] == 'N' ? 0 : 1));

                Recibo::create([
                    'numero' => $data['0'],
                    'fecha' => $data['1'],
                    'entidad_id' => $data['3'],
                    'concepto' => ($data['4'] == '' ? null : $data['4']),
                    'observacion' => ($data['5'] == '' ? null : $data['5']),
                    'efectivo' => ($data['6'] == '' ? null : $data['6']),
                    'retencion_afip' => ($data['7'] == '' ? null : $data['7']),
                    'retencion_dgr' => ($data['8'] == '' ? null : $data['8']),
                    'numero_cheque' => ($data['9'] == '' ? null : $data['9']),
                    'banco_cheque' => ($data['10'] == '' ? null : $data['10']),
                    'importe_cheque' => ($data['11'] == '' ? null : $data['11']),
                    'otro_1' => ($data['12'] == '' ? null : $data['12']),
                    'otro_1_importe' => ($data['13'] == '' ? null : $data['13']),
                    'otro_2' => ($data['14'] == '' ? null : $data['14']),
                    'otro_2_importe' => ($data['15'] == '' ? null : $data['15']),
                    'total' => $data['16'],
                    'retencion_afip_cumplida' => $re_afip,
                    'retencion_dgr_cumplida' => $re_dgr,
                    'estado' => $data['19'],
                    'generado' => ($data['20'] == '' ? null : $data['20']),
                    'firma_id' => ($data['23'] == '' ? null : $data['23']),
                    'vendedor_id' => ($data['24'] == '' ? null : $data['24']),
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
