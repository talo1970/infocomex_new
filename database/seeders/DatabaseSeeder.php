<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        /*
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory()->create([
            'name' => 'Talo',
            'email' => 'gitalo@pjn.gov.ar',
            'password' => bcrypt('password'),
        ]);
*/
        // 1
        User::factory()->create([
            'name' => 'Oscar',
            'email' => 'oscar@example.com',
            'password' => bcrypt('password'),
        ]);
        // 2
        User::factory()->create([
            'name' => 'Carlos',
            'email' => 'carlos@example.com',
            'password' => bcrypt('password'),
        ]);
        // 3
        User::factory()->create([
            'name' => 'Subcripcion',
            'email' => 'subcripcion@example.com',
            'password' => bcrypt('password'),
        ]);
        // 4
        User::factory()->create([
            'name' => 'Empresa',
            'email' => 'empresa@example.com',
            'password' => bcrypt('password'),
        ]);
        // 5
        User::factory()->create([
            'name' => 'Pablo',
            'email' => 'pablo@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'id' => 100,
            'name' => 'Talo',
            'email' => 'gitalo@pjn.gov.ar',
            'password' => bcrypt('password'),
        ]);

        Schema::disableForeignKeyConstraints();

        $this->call(ClaseSeeder::class);
        $this->call(CodigoVentaSeeder::class);
        $this->call(TipoComprobanteSeeder::class);
        $this->call(TipoEntidadSeeder::class);
        $this->call(ProvinciaSeeder::class);
        $this->call(ProductoSeeder::class);
        $this->call(MonedaSeeder::class);
        $this->call(ValorSeeder::class);
        $this->call(SucursalSeeder::class);
        $this->call(ReferenciaSeeder::class);
        $this->call(CotizacionSeeder::class);
        $this->call(TipoDocumentoSeeder::class);
        $this->call(HonorarioSeeder::class);
        $this->call(HonorarioProductoSeeder::class);
        $this->call(EntidadSeeder::class);
        $this->call(NotaSeeder::class);
        $this->call(ContactoSeeder::class);
        $this->call(EstadoSeeder::class);
        $this->call(FacturaSeeder::class);
        $this->call(FacturaMinutaSeeder::class);
        $this->call(EntidadHonorarioProductoSeeder::class);
        $this->call(EntidadProductoVendedorSeeder::class);
        $this->call(ReciboSeeder::class);

        $this->call(MinutaSeeder::class);

        Schema::enableForeignKeyConstraints();

    }
}
