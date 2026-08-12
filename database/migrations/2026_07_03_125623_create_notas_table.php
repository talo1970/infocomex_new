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
        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            $tipo_nota = ['credito', 'debito'];
            $table->enum('tipo_nota', $tipo_nota);
            $table->string('punto_venta');
            $table->unsignedBigInteger('numero');
            $table->date('fecha');
            // $table->foreignId('entidad_id')->constrained();
            $table->foreignUuid('entidad_id')->constrained();
            $table->string('concepto')->nullable();
            $table->text('observacion')->nullable();
            $table->float('efectivo', 10, 2)->default(0.0);
            $table->float('porcentaje_impuesto', 10, 2)->default(0.0)->nullable();
            $table->float('importe_impuesto', 10, 2)->default(0.0)->nullable();
            $table->float('total', 10, 2)->default(0.0);
            $table->string('estado')->nullable();
            $table->foreignId('vendedor_id')->nullable()->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
