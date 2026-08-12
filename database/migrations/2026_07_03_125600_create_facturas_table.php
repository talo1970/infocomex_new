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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->string('puesto_venta');
            $table->unsignedBigInteger('numero');
            $table->string('tipo_factura');

            // $table->foreignId('tipo_comprobante_id')->references('id')->on('entidades');
            // $table->unsignedBigInteger('tipo_comprobante_id');
            $table->date('fecha');
            $table->foreignId('producto_id')->nullable()->constrained();
            // $table->string('Tipo_Entidad');
            //$table->foreignid('entidad_id')->nullable()->constrained();
            $table->foreignUuid('entidad_id')->nullable()->constrained();

            $table->float('importe_detalle', 10, 2)->default(0.0);
            $table->float('subtotal', 10, 2)->default(0.0);
            // ver de sacar
            $table->float('bonificacion', 10, 2)->default(0.0);
            $table->unsignedInteger('porcentaje_impuesto')->nullable();
            $table->float('importe_impuesto', 10, 2)->default(0.0);
            $table->float('total', 10, 2)->default(0.0);
            $table->text('detalle')->nullable();
            $table->float('saldo_anterior', 10, 2)->default(0.0);
            // ver de precuntar a Oscar
            // $table->decimal('DetAtraso', 10, 2);
            // $table->decimal('ImpAtraso', 10, 2);
            // $table->decimal('DetCheRec', 10, 2);
            // $table->decimal('ImpCheRec', 10, 2);
            $table->float('total_a_pagar', 10, 2)->default(0.0);
            $table->string('estado')->nullable();
            // $table->string('Rec_Nro');
            $table->string('estado_pago')->nullable();
            $table->boolean('especial')->default(false);
            // $table->decimal('Det_Bonifi', 10, 2);
            $table->float('importe_minimo', 10, 2)->default(0.0);
            $table->float('tipo_cambio', 10, 2)->default(0.0);
            $table->float('total_dolares', 10, 2)->default(0.0);
            // $table->unsignedBigInteger('vendedor_id');
            $table->foreignId('vendedor_id')->nullable()->references('id')->on('users')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
