<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Route::livewire('/extrajudicial/{extrajudicial}/edit', 'pages::extrajudicial.edit')->name('extrajudicial.edit')->middleware('can:extrajudicial.editar');

    // entidades
    Route::livewire('/entidades/index', 'pages::entidades.index')->name('entidades.index');
    Route::livewire('/entidades/create', 'pages::entidades.create')->name('entidades.create');
    Route::livewire('/entidades/{entidad}/edit', 'pages::entidades.edit')->name('entidades.edit');

    // Estado
    Route::livewire('/estados/index', 'pages::estados.index')->name('estados.index');

    // Codición de Venta
    // están la administración de
    // Condición de venta, tipo de documento y Valores
    Route::livewire('/condiciones/index', 'pages::condiciones.index')->name('codicion.index');

    // Referencia
    Route::livewire('/referencias/index', 'pages::referencias.index')->name('referencias.index');

    // Monedas
    Route::livewire('/monedas/index', 'pages::monedas.index')->name('monedas.index');

    // Cotizaciones
    Route::livewire('/cotizaciones/index', 'pages::cotizaciones.index')->name('cotizaciones.index');

    // Productos
    Route::livewire('/productos/index', 'pages::productos.index')->name('productos.index');

    // Productos Honorario
    Route::livewire('/honorarios/index', 'pages::honorarios.index')->name('honorarios.index');

    // minutas Boletos
    Route::livewire('/minutas/boletos/index', 'pages::minutas.boletos.index')->name('minutas.boletos.index');

    // minutas Com6401
    Route::livewire('/minutas/6401/index', 'pages::minutas.6401.index')->name('minutas.6401.index');

    // minutas Precio de transferencia, formulario 2668 y 2672
    Route::livewire('/minutas/formularios/index/{producto?}', 'pages::minutas.formularios.index')->name('minutas.formularios');

    // minutas Suscripciones
    Route::livewire('/minutas/suscripciones/index/{producto?}', 'pages::minutas.suscripciones.index')->name('minutas.suscripciones');

    // Admin
    //  Roles
    Route::livewire('/admin/roles/index', 'pages::admin.roles.index')->name('roles.index')->middleware('can:roles.vista');
    // Usuarios
    Route::livewire('/usuario', 'pages::admin.usuarios.index')->name('usuario.index');

});

require __DIR__.'/settings.php';
