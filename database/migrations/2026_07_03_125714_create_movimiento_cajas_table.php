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
        Schema::create('movimiento_cajas', function (Blueprint $table) {
            $table->id();
            // Comprobante
            $table->foreignId('comprobante_id')->constrained('tipo_comprobantes');
            // Morphrs id del comprobante y a cual pertence
            $table->foreignId('morphable_id');
            $table->string('morphable_type');
            // $table->string('id_del_comprobante')->nullable();
            // $table->string('pto_vta')->nullable();
            // $table->string('Numero')->nullable();
            // $table->string('Tipo_Fac')->nullable();
            $table->date('fecha')->nullable();
            // ver tema del producto, si es necesario o no, ya que el movimiento de caja puede ser por un servicio o por una venta de producto
            // $table->foreignId('producto_id')->references('id')->on('productos');
            // producto_id
            // $table->foreignId('tipo_entidad_id')->references('id')->on('tipos_entidades');
            // $table->foreignId('entidad_id')->constrained('entidads');
            $table->foreignUuid('entidad_id')->constrained('entidads');
            // SubTotal
            // $table->decimal('subtotal', 10, 2)->nullable();
            // ImpImpuesto
            // $table->decimal('impuesto', 10, 2)->nullable();
            // Total
            $table->float('total', 10, 2)->default(0.0);
            $table->string('estado', 5)->nullable();

            $table->foreignId('vendedor_id')->references('id')->on('users')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_cajas');
    }
};
