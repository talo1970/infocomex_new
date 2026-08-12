<?php

namespace App\Helpers;

use Illuminate\Support\Number;

class NumerospesosHelper
{
    /**
     * format a pesos Argentinos
     *
     * @param  int  $pesos
     * @return Devuelve "$ 1.234,56
     */
    public static function format($pesos)
    {
        // $pesos = Number::currency(1234.56, 'ARS', 'es-AR');
        return Number::format(1234.56, locale: 'es-AR');
    }
}
