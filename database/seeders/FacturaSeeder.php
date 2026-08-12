<?php

namespace Database\Seeders;

use App\Models\Factura;
use Illuminate\Database\Seeder;

class FacturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // facturas :: truncate ();
        $csvFile = fopen(base_path('database/data/factura.csv'), 'r');
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            // var_dump($data);
            if (! $firstline) {
                Factura::create([
                    'id' => $data['0'],
                    'puesto_venta' => $data['1'],
                    'numero' => $data['2'],
                    'tipo_factura' => ($data['3'] == '' ? null : $data['3']),
                    'fecha' => ($data['5'] == '' ? null : $data['5']),
                    'producto_id' => ($data['6'] == '' ? null : $data['6']),
                    'entidad_id' => ($data['8'] == '' ? null : $data['8']),
                    'importe_detalle' => ($data['9'] == '' ? '0.0' : $data['9']),
                    'subtotal' => ($data['10'] == '' ? '0.0' : $data['10']),
                    'bonificacion' => ($data['11'] == '' ? '0.0' : $data['11']),
                    'porcentaje_impuesto' => ($data['12'] == '' ? null : $data['12']),
                    'importe_impuesto' => ($data['13'] == '' ? '0.0' : $data['13']),
                    'total' => ($data['14'] == '' ? '0.0' : $data['14']),
                    'detalle' => ($data['15'] == '' ? null : $data['15']),
                    'saldo_anterior' => ($data['16'] == '' ? '0.0' : $data['16']),
                    'total_a_pagar' => ($data['21'] == '' ? '0.0' : $data['21']),
                    'estado' => ($data['22'] == '' ? null : $data['22']),
                    'estado_pago' => ($data['24'] == '' ? null : $data['24']),
                    'especial' => ($data['25'] == '' ? 1 : 0),
                    'importe_minimo' => ($data['29'] == '' ? '0.0' : $data['29']),
                    'tipo_cambio' => ($data['30'] == '' ? '0.0' : $data['30']),
                    'total_dolares' => ($data['31'] == '' ? '0.0' : $data['31']),
                    'vendedor_id' => ($data['32'] == '' ? null : $data['32']),
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
