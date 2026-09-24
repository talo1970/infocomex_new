<?php

namespace Database\Seeders;

use App\Models\configuracion;
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
        $this->call(RoleSeeder::class);

        $this->call(MinutaSeeder::class);

        Schema::enableForeignKeyConstraints();
        // 1
        User::factory()->create([
            'name' => 'Oscar',
            'email' => 'oscar@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('Administrador');
        // 2
        User::factory()->create([
            'name' => 'Carlos',
            'email' => 'carlos@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('Administrador');
        // 3
        User::factory()->create([
            'name' => 'Subcripcion',
            'email' => 'subcripcion@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('Vendedor');
        // 4
        User::factory()->create([
            'name' => 'Empresa',
            'email' => 'empresa@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('Vendedor');
        // 5
        User::factory()->create([
            'name' => 'Pablo',
            'email' => 'pablo@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('Vendedor');

        User::create([
            'id' => 100,
            'name' => 'Talo',
            'email' => 'gitalo@pjn.gov.ar',
            'password' => bcrypt('password'),
        ])->assignRole('Administrador');

        configuracion::create([
            'nombre' => 'modo_facturación',
            'valor_texto' => 'mensual',
        ]);
        configuracion::create([
            'nombre' => 'dia_corte',
            'valor_numerico' => 16,
        ]);
        configuracion::create([
            'nombre' => 'fecha_migracion',
            'valor_fecha' => '2021-05-10',
        ]);
        configuracion::create([
            'nombre' => 'fecha_migracion_archivo',
            'valor_fecha' => '2011-04-27',
        ]);
        configuracion::create([
            'nombre' => 'dolar_factura_minima',
            'valor_importe' => 50.00,
        ]);
        configuracion::create([
            'nombre' => 'dolar_factura_maximo',
            'valor_importe' => 500.00,
        ]);
        configuracion::create([
            'nombre' => 'tipo_cambio_factura',
            'valor_importe' => 1487.50,
        ]);
        configuracion::create([
            'nombre' => 'valor_suscripcion',
            'valor_importe' => 145,
        ]);
        configuracion::create([
            'nombre' => 'valor_suscripcion_bcra',
            'valor_importe' => 12070,
        ]);
        configuracion::create([
            'nombre' => 'valor_suscripcion_doble',
            'valor_importe' => 115,
        ]);
        configuracion::create([
            'nombre' => 'anio_inicio',
            'valor_numerico' => 2014,
        ]);
        configuracion::create([
            'nombre' => 'directorio',
            'valor_texto' => 'N:\infocomex',
        ]);
        configuracion::create([
            'nombre' => 'periodo_cambio_contraseña',
            'valor_numerico' => 3,
        ]);

    }
}
