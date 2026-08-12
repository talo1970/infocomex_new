<?php

namespace Database\Seeders;

use App\Models\Honorario;
use Illuminate\Database\Seeder;

class HonorarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $honoriarios = [
            ['nombre' => 'Honorario 1', 'importe' => 1.00],
            ['nombre' => 'Honorario 2', 'importe' => 2.00],
            ['nombre' => 'Honorario 3', 'importe' => 3.00],
            ['nombre' => 'Honorario 4', 'importe' => 4.00],
            ['nombre' => 'Honorario 5', 'importe' => 5.00],
            ['nombre' => 'Honorario 6', 'importe' => 6.00],
            ['nombre' => 'Honorario 7', 'importe' => 7.00],
            ['nombre' => 'Honorario 8', 'importe' => 8.00],
            ['nombre' => 'Honorario 9', 'importe' => 9.00],
            ['nombre' => 'Honorario 10', 'importe' => 10.00],
        ];

        foreach ($honoriarios as $honorario) {
            Honorario::create($honorario);
        }
    }
}
