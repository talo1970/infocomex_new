<?php

namespace Database\Seeders;

use App\Models\Entidad;
use Illuminate\Database\Seeder;

class EntidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // entidades :: truncate ();
        $csvFile = fopen(base_path('database/data/entidades.csv'), 'r');
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            // var_dump($data);
            if (! $firstline) {
                Entidad::create([
                    'id_old' => $data['0'],
                    'tipo_entidad_id' => $data['1'],
                    'razon_social' => $data['2'],
                    'cuit' => ($data['3'] == '' ? null : $data['3']),
                    'porcentaje_comision' => ($data['4'] == '' ? '0.0' : $data['4']),
                    'telefono' => ($data['5'] == '' ? null : $data['5']),
                    'contacto' => ($data['6'] == '' ? null : $data['6']),
                    'mail' => ($data['7'] == '' ? null : $data['7']),
                    'domicilio' => ($data['8'] == '' ? null : $data['8']),
                    'numero' => ($data['9'] == '' ? null : $data['9']),
                    'departamento_piso' => ($data['10'] == '' ? null : $data['10']),
                    'codigo_postal' => ($data['11'] == '' ? null : $data['11']),
                    'localidad' => ($data['12'] == '' ? null : $data['12']),
                    'provincia_id' => $data['13'],
                    'pais' => ($data['14'] == '' ? null : $data['14']),

                    //            $modo_factura = ['Bimestral', 'Mensual'];
                    'modo_factura' => ($data['18'] == 'B' ? 'Bimestral' : 'Mensual'),
                    'codigo_proveedor' => ($data['19'] == '' ? null : $data['19']),
                    'observacion' => ($data['20'] == '' ? null : $data['20']),
                    // tipo de comprobante que tipo de facura es el cliente
                    'tipo_factura_id' => ($data['25'] == '' ? null : $data['25']),
                    'facturacion_limite' => ($data['28'] == '' ? 0 : $data['28']),
                    'facturacion_minima' => ($data['30'] == '' ? 0 : $data['30']),
                    'facturacion_fija' => ($data['31'] == '' ? 0 : $data['31']),

                    // $tipo_operacion = ['Exportación', 'Importación'];
                    'tipo_operacion' => ($data['29'] == 'E' ? 'Exportación' : 'Importación'),
                    // 'vendedor_id' => $data [ '27' ],
                    'vendedor_id' => 4,
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
