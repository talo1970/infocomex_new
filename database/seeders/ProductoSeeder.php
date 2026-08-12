<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            ['nombre' => 'BOLETOS', 'letra' => 'C', 'honorario' => 0, 'abreviado' => 'Boletos', 'color' => '14737632', 'color_web' => 'zinc', 'tabla' => 'boletos'],
            ['nombre' => 'BONO FISCAL', 'letra' => 'F', 'honorario' => 0, 'abreviado' => 'B. Fiscal', 'color' => '', 'color_web' => null, 'tabla' => 'boletos_bono'],
            ['nombre' => 'COM A 6401', 'letra' => 'G', 'honorario' => 1, 'abreviado' => 'C.A - 6401', 'color' => '12632319', 'color_web' => 'fuchsia', 'tabla' => 'boleto_3602'],
            ['nombre' => 'ORDEN DE PAGOS NUEVAS', 'letra' => 'H', 'honorario' => 0, 'abreviado' => 'O. Pago', 'color' => '', 'color_web' => null, 'tabla' => 'orden_pago'],
            ['nombre' => 'ORDEN DE PAGOS VENCIDAS', 'letra' => 'I', 'honorario' => 0, 'abreviado' => 'O. Pago vto', 'color' => '', 'color_web' => null, 'tabla' => 'orden_pago_v'],
            ['nombre' => 'APP Suscripción DVH', 'letra' => 'D', 'honorario' => 1, 'abreviado' => 'Subcrip', 'color' => '49344', 'color_web' => 'green', 'tabla' => 'boleto_dape'],
            ['nombre' => 'SUBCRIPCIONES BCRA', 'letra' => 'U', 'honorario' => 1, 'abreviado' => 'Subcrip BCRA', 'color' => '12632064', 'color_web' => 'sky', 'tabla' => 'subcrip_bcra'],
            ['nombre' => 'SUBCRIPCIONES DOBLES', 'letra' => '8', 'honorario' => 1, 'abreviado' => 'Subcrip Doble', 'color' => '16777152', 'color_web' => null, 'tabla' => 'subcrip_doble'],
            ['nombre' => 'PRECIO DE TRANSFERENCIA', 'letra' => '9', 'honorario' => 1, 'abreviado' => 'P Transf.', 'color' => '8421631', 'color_web' => 'red', 'tabla' => 'precio_transferencia'],
            ['nombre' => 'FORMULARIO-2668', 'letra' => '10', 'honorario' => 1, 'abreviado' => 'F - 2668', 'color' => '8421631', 'color_web' => 'red', 'tabla' => 'minuta_f2668'],
            ['nombre' => 'FORMULARIO-2672', 'letra' => '11', 'honorario' => 1, 'abreviado' => 'F - 2672', 'color' => '8421631', 'color_web' => 'red', 'tabla' => 'minuta_f2672'],
        ];

        foreach ($productos as $producto) {
            Producto::create([
                'nombre' => $producto['nombre'],
                'letra' => $producto['letra'],
                'honorario' => $producto['honorario'],
                'abreviado' => $producto['abreviado'],
                'color' => $producto['color'],
                'color_web' => $producto['color_web'],
                'tabla' => $producto['tabla'],
            ]);
        }

    }
}
