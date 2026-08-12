<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('entidads', function (Blueprint $table) {
            //$table->id();
            $table->uuid('id')->primary();
            $table->uuid('id_old');
            $table->foreignId('tipo_entidad_id')->constrained();
            $table->string('razon_social', 100); // ->unique();
            $table->string('cuit', 20)->nullable();
            $table->integer('porcentaje_comision')->default(0);
            $table->string('telefono', 150)->nullable();
            $table->string('contacto', 150)->nullable();
            $table->string('mail', 150)->nullable();
            $table->string('domicilio', 200)->nullable();
            $table->string('numero', 50)->nullable();
            $table->string('departamento_piso', 150)->nullable();
            $table->string('codigo_postal', 150)->nullable();
            $table->string('localidad', 150)->nullable();
            $table->foreignId('provincia_id')->constrained();
            $table->string('pais', 50)->nullable();
            // el contacto 2 es en la tabla de contacto
            $modo_factura = ['Bimestral', 'Mensual'];
            $table->enum('modo_factura', $modo_factura)->nullable()->default(null);
            $table->string('codigo_proveedor', 50)->nullable();
            $table->string('observacion')->nullable();
            // tipo de comprobante que tipo de facura es el cliente
            $table->foreignId('tipo_factura_id')->nullable()->references('id')->on('tipo_comprobantes');
            $table->integer('facturacion_limite')->nullable()->default(0);
            $table->integer('facturacion_minima')->nullable()->default(0);
            $table->integer('facturacion_fija')->nullable()->default(0);

            $tipo_operacion = ['Exportación', 'Importación'];
            $table->enum('tipo_operacion', $tipo_operacion)->default(null);

            $table->foreignId('vendedor_id')->nullable()->references('id')->on('users');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entidads');
    }
};
