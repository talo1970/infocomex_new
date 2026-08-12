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
        Schema::create('factura_recibos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_id')->constrained('facturas');
            $table->foreignId('recibo_id')->constrained('recibos');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factura_recibos');
    }
};
