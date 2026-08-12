<?php

namespace Database\Seeders;

use App\Models\Valor;
use Illuminate\Database\Seeder;

class ValorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $valores = [
            ['id' => '1', 'nombre' => 'T+1', 'seleccion' => 1],
            ['id' => '2', 'nombre' => 'T+0', 'seleccion' => 0],
            ['id' => '3', 'nombre' => 'T+2', 'seleccion' => 0],
            ['id' => '4', 'nombre' => 'T+3', 'seleccion' => 0],
            ['id' => '5', 'nombre' => 'T+4', 'seleccion' => 0],
            ['id' => '6', 'nombre' => 'T+5', 'seleccion' => 0],
            ['id' => '7', 'nombre' => '48 Horas', 'seleccion' => 0],
            ['id' => '8', 'nombre' => '72 Horas', 'seleccion' => 0],
        ];
        foreach ($valores as $valor) {
            Valor::create($valor);
        }
    }
}
