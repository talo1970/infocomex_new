<?php

namespace Database\Seeders;

use App\Models\TipoComprobante;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoComprobanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoComprobante::create(['nombre' => 'Factura A', 'tipo' => 'factura', 'abreviado' => 'Fac A']);
        TipoComprobante::create(['nombre' => 'Recibo', 'tipo' => 'recibo', 'abreviado' => 'Rec']);
        TipoComprobante::create(['nombre' => 'Nota de Credito', 'tipo' => 'nota_credito', 'abreviado' => 'NC']);
        TipoComprobante::create(['nombre' => 'Nota de Debito', 'tipo' => 'nota_debito', 'abreviado' => 'ND']);
        TipoComprobante::create(['nombre' => 'Factura B', 'tipo' => 'factura', 'abreviado' => 'Fac B']);
        TipoComprobante::create(['nombre' => 'Factura AD', 'tipo' => 'factura', 'abreviado' => 'Fac Do']);
        TipoComprobante::create(['nombre' => 'Factura E', 'tipo' => 'factura', 'abreviado' => 'Fac E']);
    }
}
