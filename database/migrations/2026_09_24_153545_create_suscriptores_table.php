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
            $table->foreignUuid('minuta_id')->constrained();
            // $table->foreignId('tipo_comprobante_id')->references('id')->on('tipo_comprobantes');
            $table->string('nombre', 100)->nullable();
            $table->date('inicio')->nullable();
            $table->date('vencimiento')->nullable();
            $table->integer('dias')->nullable();
            $table->float('importe_comision', 10, 2)->nullable();
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
