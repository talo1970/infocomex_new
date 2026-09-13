<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role1 = Role::create(['name' => 'super-admin']);
        $role2 = Role::create(['name' => 'Administrador']);
        $role3 = Role::create(['name' => 'Vendedor']);

        $role4 = Role::create(['name' => 'minutas']);
        $role5 = Role::create(['name' => 'boletos_cambio']);
        $role6 = Role::create(['name' => '6401']);
        $role7 = Role::create(['name' => 'Precio transferencia']);
        $role8 = Role::create(['name' => 'F-2668']);
        $role9 = Role::create(['name' => 'F-2672']);

        $role10 = Role::create(['name' => 'Suscripciones']);
        $role11 = Role::create(['name' => 'Suscripciones BCRA']);
        $role12 = Role::create(['name' => 'Suscripciones Doble']);

        $role8 = Role::create(['name' => 'Estado cuenta vendedor']);
        $role8 = Role::create(['name' => 'Facturas Vendedor']);

        $role8 = Role::create(['name' => 'Consulta']);

        // Permission::create(['name' => 'admin.home'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'dashboard', 'descripcion' => 'Ver Escritorio', 'guard_name' => 'web'])->syncRoles([$role1, $role2, $role3]);
        Permission::create(['name' => 'administración', 'descripcion' => 'Menú administración', 'guard_name' => 'web'])->syncRoles([$role1, $role2]);

        // entidades
        Permission::create(['name' => 'entidades.vista', 'descripcion' => 'Ver Listado de entidades', 'guard_name' => 'web'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'entidades.crear', 'descripcion' => 'Crear entidades', 'guard_name' => 'web'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'entidades.editar', 'descripcion' => 'Editar entidades', 'guard_name' => 'web'])->syncRoles([$role1, $role2]);

        // moneda
        Permission::create(['name' => 'moneda.vista', 'descripcion' => 'Ver Listado de moneda', 'guard_name' => 'web'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'moneda.crear', 'descripcion' => 'Crear moneda', 'guard_name' => 'web'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'moneda.editar', 'descripcion' => 'Editar moneda', 'guard_name' => 'web'])->syncRoles([$role1, $role2]);

        // roles
        Permission::create(['name' => 'roles.vista', 'descripcion' => 'Ver roles', 'guard_name' => 'web'])->syncRoles([$role1, $role2]);

        // Notificaciones
        //Permission::create(['name' => 'notificaciones.vista', 'descripcion' => 'Ver panel de notificaciones', 'guard_name' => 'web'])->syncRoles([$role1, $role2, $role3]);

    }
}
