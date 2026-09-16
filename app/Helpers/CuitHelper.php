<?php

    namespace App\Helpers;

    class CuitHelper
    {
        /**
         * format a CUIT/CUIL number to include dashes
         *
         * @param int $cuit
         *
         * @return string
         */
        public static function format($cuit)
        {
            $cuit = substr_replace((string) $cuit, '-', -1, 0);

            return substr_replace($cuit, '-', 2, 0);
        }

    }
