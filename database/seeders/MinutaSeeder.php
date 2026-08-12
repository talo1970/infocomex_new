<?php

namespace Database\Seeders;

use App\Models\Minuta;
use Illuminate\Database\Seeder;

class MinutaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // boletos :: truncate ();
        $csvFile = fopen(base_path('database/data/boletos.csv'), 'r');
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            // var_dump($data);
            if (! $firstline) {
                Minuta::create([
                    'producto_id' => 1,
                    'id_original' => $data['0'],
                    'numero' => ($data['1'] == '' ? null : $data['1']),
                    'periodo' => ($data['3'] == '' ? null : $data['3']),
                    'fecha' => $data['2'],
                    'estado_id' => ($data['5'] == '' ? 10 : $data['5']),
                    'comprador_id' => ($data['7'] == '' ? null : $data['7']),
                    'vendedor_id' => ($data['9'] == '' ? null : $data['9']),
                    'moneda_id' => ($data['10'] == '' ? null : $data['10']),
                    'clase_id' => ($data['11'] == '' ? null : $data['11']),
                    'valor_id' => ($data['12'] == '' ? null : $data['12']),
                    'importe' => ($data['13'] == '' ? null : $data['13']),
                    'tipo_cambio' => ($data['14'] == '' ? null : $data['14']),
                    'equivalente' => ($data['15'] == '' ? null : $data['15']),
                    'referencia_id' => ($data['16'] == '' ? null : $data['16']),
                    // 'tipo_documento_id' => $data [ '0' ],
                    'observacion' => ($data['17'] == '' ? null : $data['17']),
                    'comision_comprador' => ($data['18'] == '' ? null : $data['18']),
                    'importe_comision_comprador' => ($data['19'] == '' ? null : $data['19']),
                    'comision_vendedor' => ($data['20'] == '' ? null : $data['20']),
                    'importe_comision_vendedor' => ($data['21'] == '' ? null : $data['21']),
                    'operacion' => ($data['29'] == 'I' ? 'Importación' : ($data['29'] == 'E' ? 'Exportación' : 'Mercado')),
                    'factura_cliente_id' => ($data['22'] == '' ? null : $data['22']),
                    'factura_banco_id' => ($data['35'] == '' ? null : $data['35']),
                    'usuario_vendedor_id' => ($data['38'] == '' ? null : $data['38']),
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);

        // 6401 :: truncate ();
        $csvFile = fopen(base_path('database/data/boletos_3602.csv'), 'r');
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            // var_dump($data);
            if (! $firstline) {
                Minuta::create([
                    'producto_id' => 3,
                    'id_original' => $data['0'],
                    'numero' => ($data['1'] == '' ? null : $data['1']),
                    'fecha' => $data['2'],
                    'periodo' => ($data['3'] == '' ? null : $data['3']),
                    'estado_id' => ($data['4'] == null ? 10 : $data['4']),
                    'entidad_cliente_id' => ($data['5'] == '' ? null : $data['5']),
                    'bcra_id' => ($data['6'] == '' ? null : $data['6']),
                    'periodo_desde' => ($data['7'] == '' ? null : $data['7']),
                    'periodo_hasta' => ($data['8'] == '' ? null : $data['8']),
                    'periodo_cantidad' => ($data['9'] == '' ? null : $data['9']),
                    'observacion' => ($data['10'] == '' ? null : $data['10']),
                    'importe_comision_unidad' => ($data['11'] == '' ? null : $data['11']),
                    'importe_comision_dolares' => ($data['12'] == '' ? null : $data['12']),
                    'tipo_cambio' => ($data['13'] == '' ? null : $data['13']),
                    'importe_comision' => ($data['14'] == '' ? null : $data['14']),
                    'factura_cliente_id' => ($data['15'] == '' ? null : $data['15']),
                    'usuario_vendedor_id' => ($data['22'] == '' ? null : $data['22']),
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);

        // pt :: truncate ();
        $csvFile = fopen(base_path('database/data/precio_transferencia.csv'), 'r');
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            // var_dump($data);
            if (! $firstline) {
                Minuta::create([
                    'producto_id' => 9,
                    'id_original' => $data['0'],
                    'numero' => ($data['1'] == '' ? null : $data['1']),
                    'fecha' => $data['2'],
                    'periodo' => ($data['3'] == '' ? null : $data['3']),
                    'estado_id' => ($data['4'] == null ? 10 : $data['4']),
                    'entidad_cliente_id' => ($data['5'] == '' ? null : $data['5']),
                    'arca' => ($data['6'] == '' ? 0 : 1),
                    'anio_desde' => ($data['7'] == '' ? null : $data['7']),
                    'anio_hasta' => ($data['8'] == '' ? null : $data['8']),
                    'anio_cantidad' => ($data['9'] == '' ? null : $data['9']),
                    'observacion' => ($data['10'] == '' ? null : $data['10']),
                    'importe_comision_unidad' => ($data['11'] == '' ? null : $data['11']),
                    'importe_comision_dolares' => ($data['12'] == '' ? null : $data['12']),
                    'tipo_cambio' => ($data['13'] == '' ? null : $data['13']),
                    'importe_comision' => ($data['14'] == '' ? null : $data['14']),
                    'factura_cliente_id' => ($data['15'] == '' ? null : $data['15']),
                    'usuario_vendedor_id' => ($data['22'] == '' ? null : $data['22']),
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);

        // minuta_f2668 :: truncate ();
        $csvFile = fopen(base_path('database/data/minuta_f2668.csv'), 'r');
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            // var_dump($data);
            if (! $firstline) {
                Minuta::create([
                    'producto_id' => 10,
                    'id_original' => $data['0'],
                    'numero' => ($data['1'] == '' ? null : $data['1']),
                    'fecha' => $data['2'],
                    'periodo' => $data['3'],
                    'estado_id' => ($data['4'] == null ? 10 : $data['4']),
                    'entidad_cliente_id' => ($data['5'] == '' ? null : $data['5']),
                    'arca' => ($data['6'] == '' ? 0 : 1),
                    'anio_desde' => ($data['7'] == '' ? null : $data['7']),
                    'anio_hasta' => ($data['8'] == '' ? null : $data['8']),
                    'anio_cantidad' => ($data['9'] == '' ? null : $data['9']),
                    'observacion' => ($data['10'] == '' ? null : $data['10']),
                    'importe_comision_unidad' => ($data['11'] == '' ? null : $data['11']),
                    'importe_comision_dolares' => ($data['12'] == '' ? null : $data['12']),
                    'tipo_cambio' => ($data['13'] == '' ? null : $data['13']),
                    'importe_comision' => ($data['14'] == '' ? null : $data['14']),
                    'factura_cliente_id' => ($data['15'] == '' ? null : $data['15']),
                    'usuario_vendedor_id' => ($data['22'] == '' ? null : $data['22']),
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);

        // minuta_f2672 :: truncate ();
        $csvFile = fopen(base_path('database/data/minuta_f2672.csv'), 'r');
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            // var_dump($data);
            if (! $firstline) {
                Minuta::create([
                    'producto_id' => 11,
                    'id_original' => $data['0'],
                    'numero' => ($data['1'] == '' ? null : $data['1']),
                    'fecha' => $data['2'],
                    'periodo' => $data['3'],
                    'estado_id' => ($data['4'] == null ? 10 : $data['4']),
                    'entidad_cliente_id' => ($data['5'] == '' ? null : $data['5']),
                    'arca' => ($data['6'] == '' ? 0 : 1),
                    'anio_desde' => ($data['7'] == '' ? null : $data['7']),
                    'anio_hasta' => ($data['8'] == '' ? null : $data['8']),
                    'anio_cantidad' => ($data['9'] == '' ? null : $data['9']),
                    'observacion' => ($data['10'] == '' ? null : $data['10']),
                    'importe_comision_unidad' => ($data['11'] == '' ? null : $data['11']),
                    'importe_comision_dolares' => ($data['12'] == '' ? null : $data['12']),
                    'tipo_cambio' => ($data['13'] == '' ? null : $data['13']),
                    'importe_comision' => ($data['14'] == '' ? null : $data['14']),
                    'factura_cliente_id' => ($data['15'] == '' ? null : $data['15']),
                    'usuario_vendedor_id' => ($data['22'] == '' ? null : $data['22']),
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);

        // subcripciones :: truncate ();
        $csvFile = fopen(base_path('database/data/boleto_dape.csv'), 'r');
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            // var_dump($data);
            if (! $firstline) {
                Minuta::create([
                    'producto_id' => 6,
                    'id_original' => $data['0'],
                    'numero' => ($data['1'] == '' ? null : $data['1']),
                    'fecha' => $data['2'],
                    'estado_id' => ($data['4'] == null ? 10 : $data['4']),
                    'entidad_cliente_id' => ($data['5'] == '' ? null : $data['5']),
                    'cantidad' => ($data['6'] == '' ? null : $data['6']),
                    'importe' => ($data['7'] == '' ? null : $data['7']),
                    'plazo' => ($data['8'] == '' ? null : $data['8']),
                    'fecha_vencimiento' => ($data['9'] == '' ? null : $data['9']),
                    'tipo_documento_id' => ($data['10'] == '' ? null : $data['10']),
                    'referencia_id' => ($data['11'] == '' ? null : $data['11']),
                    'observacion' => ($data['12'] == '' ? null : $data['12']),
                    'importe_comision_unidad' => ($data['13'] == '' ? null : $data['13']),
                    'importe_comision_dolares' => ($data['14'] == '' ? null : $data['14']),
                    'tipo_cambio' => ($data['15'] == '' ? null : $data['15']),
                    'importe_comision' => ($data['16'] == '' ? null : $data['16']),
                    'factura_cliente_id' => ($data['18'] == '' ? null : $data['18']),
                    'usuario_vendedor_id' => ($data['25'] == '' ? null : $data['25']),
                    'padre_id' => ($data['17'] == '' ? null : $data['17']),
                    'hijo_id' => ($data['26'] == '' ? null : $data['26']),
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);

        // subcripciones :: truncate ();
        $csvFile = fopen(base_path('database/data/boleto_dape.csv'), 'r');
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            // var_dump($data);
            if (! $firstline) {
                Minuta::create([
                    'producto_id' => 6,
                    'id_original' => $data['0'],
                    'numero' => ($data['1'] == '' ? null : $data['1']),
                    'fecha' => $data['2'],
                    'estado_id' => ($data['4'] == null ? 10 : $data['4']),
                    'entidad_cliente_id' => ($data['5'] == '' ? null : $data['5']),
                    'cantidad' => ($data['6'] == '' ? null : $data['6']),
                    'importe' => ($data['7'] == '' ? null : $data['7']),
                    'plazo' => ($data['8'] == '' ? null : $data['8']),
                    'fecha_vencimiento' => ($data['9'] == '' ? null : $data['9']),
                    'tipo_documento_id' => ($data['10'] == '' ? null : $data['10']),
                    'referencia_id' => ($data['11'] == '' ? null : $data['11']),
                    'observacion' => ($data['12'] == '' ? null : $data['12']),
                    'importe_comision_unidad' => ($data['13'] == '' ? null : $data['13']),
                    'importe_comision_dolares' => ($data['14'] == '' ? null : $data['14']),
                    'tipo_cambio' => ($data['15'] == '' ? null : $data['15']),
                    'importe_comision' => ($data['16'] == '' ? null : $data['16']),
                    'factura_cliente_id' => ($data['18'] == '' ? null : $data['18']),
                    'usuario_vendedor_id' => ($data['25'] == '' ? null : $data['25']),
                    'padre_id' => ($data['17'] == '' ? null : $data['17']),
                    'hijo_id' => ($data['26'] == '' ? null : $data['26']),
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);

        // subcripciones_bcra :: truncate ();
        $csvFile = fopen(base_path('database/data/boleto_dape.csv'), 'r');
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            // var_dump($data);
            if (! $firstline) {
                Minuta::create([
                    'producto_id' => 7,
                    'id_original' => $data['0'],
                    'numero' => ($data['1'] == '' ? null : $data['1']),
                    'fecha' => $data['2'],
                    'estado_id' => ($data['4'] == null ? 10 : $data['4']),
                    'entidad_cliente_id' => ($data['5'] == '' ? null : $data['5']),
                    'cantidad' => ($data['6'] == '' ? null : $data['6']),
                    'importe' => ($data['7'] == '' ? null : $data['7']),
                    'plazo' => ($data['8'] == '' ? null : $data['8']),
                    'fecha_vencimiento' => ($data['9'] == '' ? null : $data['9']),
                    'tipo_documento_id' => ($data['10'] == '' ? null : $data['10']),
                    'referencia_id' => ($data['11'] == '' ? null : $data['11']),
                    'observacion' => ($data['12'] == '' ? null : $data['12']),
                    'importe_comision_unidad' => ($data['13'] == '' ? null : $data['13']),
                    'importe_comision_dolares' => ($data['14'] == '' ? null : $data['14']),
                    'tipo_cambio' => ($data['15'] == '' ? null : $data['15']),
                    'importe_comision' => ($data['16'] == '' ? null : $data['16']),
                    'factura_cliente_id' => ($data['17'] == '' ? null : $data['17']),
                    'usuario_vendedor_id' => ($data['24'] == '' ? null : $data['24']),
                    'hijo_id' => ($data['25'] == '' ? null : $data['25']),
                    'padre_id' => $data['26'],
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);

        // subcripciones_bcra :: truncate ();
        $csvFile = fopen(base_path('database/data/subcrip_doble.csv'), 'r');
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            // var_dump($data);
            if (! $firstline) {
                Minuta::create([
                    'producto_id' => 8,
                    'id_original' => $data['0'],
                    'numero' => ($data['1'] == '' ? null : $data['1']),
                    'fecha' => $data['2'],
                    'estado_id' => ($data['4'] == null ? 10 : $data['4']),
                    'entidad_cliente_id' => ($data['5'] == '' ? null : $data['5']),
                    'cantidad' => ($data['6'] == '' ? null : $data['6']),
                    'importe' => ($data['7'] == '' ? null : $data['7']),
                    'plazo' => ($data['8'] == '' ? null : $data['8']),
                    'fecha_vencimiento' => ($data['9'] == '' ? null : $data['9']),
                    'tipo_documento_id' => ($data['10'] == '' ? null : $data['10']),
                    'referencia_id' => ($data['11'] == '' ? null : $data['11']),
                    'observacion' => ($data['12'] == '' ? null : $data['12']),
                    'importe_comision_unidad' => ($data['13'] == '' ? null : $data['13']),
                    'importe_comision_dolares' => ($data['14'] == '' ? null : $data['14']),
                    'tipo_cambio' => ($data['15'] == '' ? null : $data['15']),
                    'importe_comision' => ($data['16'] == '' ? null : $data['16']),
                    'factura_cliente_id' => ($data['17'] == '' ? null : $data['17']),
                    'usuario_vendedor_id' => ($data['24'] == '' ? null : $data['24']),
                    'hijo_id' => ($data['25'] == '' ? null : $data['25']),
                    'padre_id' => ($data['26'] == '' ? null : $data['26']),
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
