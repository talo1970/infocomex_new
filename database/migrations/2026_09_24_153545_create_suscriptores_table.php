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
        Schema::create('suscriptores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('minuta_id')->constrained('minutas');
            // $table->foreignId('tipo_comprobante_id')->references('id')->on('tipo_comprobantes');
            $table->string('nombre', 100)->nullable();
            $table->date('inicio');
            $table->date('fin');
            $table->integer('dias')->nullable();
            $table->float('importe_comision', 10, 2)->default(0.0)->nullable();
            $table->boolean('completo')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suscriptores');
    }
};
