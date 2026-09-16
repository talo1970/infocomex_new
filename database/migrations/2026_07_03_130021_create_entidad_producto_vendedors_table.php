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
        Schema::create('entidad_producto_vendedors', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('entidad_id')->constrained('entidads');
            $table->foreignUuid('entidad_id')->constrained('entidads');
            $table->foreignId('producto_id')->constrained('productos');
            $table->foreignId('vendedor_id')->references('id')->on('users');
            $table->unique(
                ['entidad_id', 'producto_id', 'vendedor_id'],
                'epv_entidad_producto_vendedor_unique'
            );

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entidad_producto_vendedors');
    }
};
