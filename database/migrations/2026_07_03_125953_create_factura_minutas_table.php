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
        Schema::create('factura_minutas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_id')->constrained('facturas');
            $table->foreignUuid('minuta_id')->constrained('minutas');
            // ver
            $table->foreignId('producto_id')->nullable()->constrained('productos');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factura_minutas');
    }
};
