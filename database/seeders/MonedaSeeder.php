<?php

namespace Database\Seeders;

use App\Models\Moneda;
use Illuminate\Database\Seeder;

class MonedaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $monedas = [
            ['nombre' => 'Dólar Estadounidense', 'simbolo' => 'USD', 'pais' => 'EEUU', 'seleccion' => '1'],
            ['nombre' => 'Euro', 'simbolo' => 'EUR', 'pais' => 'Unidad Monetaria Europea', 'seleccion' => '0'],
            ['nombre' => 'Libra Esterlina', 'simbolo' => 'GBP', 'pais' => 'Inglaterra', 'seleccion' => '0'],
            ['nombre' => 'Franco Suizo', 'simbolo' => 'CHF', 'pais' => 'Suiza', 'seleccion' => '0'],
            ['nombre' => 'Yen', 'simbolo' => 'JPY', 'pais' => 'Japón', 'seleccion' => '0'],
            ['nombre' => 'Dólar Canadiense', 'simbolo' => 'CAD', 'pais' => 'Canada', 'seleccion' => '0'],
            ['nombre' => 'Corona Danesa', 'simbolo' => 'DKK', 'pais' => 'Dinamarca', 'seleccion' => '0'],
            ['nombre' => 'Corona Noruega', 'simbolo' => 'NKR', 'pais' => 'Noruega', 'seleccion' => '0'],
            ['nombre' => 'Corona Sueca', 'simbolo' => 'SEK', 'pais' => 'Suecia', 'seleccion' => '0'],
            ['nombre' => 'Peso Chileno', 'simbolo' => 'CLP', 'pais' => 'Chile', 'seleccion' => '0'],
            ['nombre' => 'Real', 'simbolo' => 'BRL', 'pais' => 'Brasil', 'seleccion' => '0'],
            ['nombre' => 'Peso Uruguayo', 'simbolo' => 'UYU', 'pais' => 'Uruguay', 'seleccion' => '0'],
            ['nombre' => 'Marco Aleman', 'simbolo' => 'DEM', 'pais' => 'Alemania', 'seleccion' => '0'],
            ['nombre' => 'Dólar Australiano', 'simbolo' => 'AUD', 'pais' => 'Australia', 'seleccion' => '0'],
            ['nombre' => 'Dólares Billetes', 'simbolo' => 'USBIL', 'pais' => 'Estados Unidos', 'seleccion' => '0'],
            ['nombre' => 'Yuan', 'simbolo' => 'CNY', 'pais' => 'CHINA - Rep Popular de China', 'seleccion' => '0'],
            ['nombre' => 'DOLAR MEP', 'simbolo' => 'MEP', 'pais' => 'ARGENTINA', 'seleccion' => '0'],
        ];

        foreach ($monedas as $moneda) {
            Moneda::create([
                'nombre' => $moneda['nombre'],
                'simbolo' => $moneda['simbolo'],
                'pais' => $moneda['pais'],
                'seleccion' => $moneda['seleccion'],
            ]);
        }
    }
}
