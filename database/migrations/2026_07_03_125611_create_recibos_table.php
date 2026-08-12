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
        Schema::create('recibos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('numero');
            $table->date('fecha');
            // $table->foreignId('entidad_id')->constrained();
            $table->foreignUuid('entidad_id')->constrained();
            $table->string('concepto')->nullable();
            $table->text('observacion')->nullable();
            $table->float('efectivo', 10, 2)->default(0.0)->nullable();
            $table->float('retencion_afip', 10, 2)->default(0.0)->nullable();
            $table->float('retencion_dgr', 10, 2)->default(0.0)->nullable();
            $table->string('numero_cheque')->nullable();

            // $table->string('banco_cheque')->nullable();
            // seleccionar banco de una lista de entidades que sean bancos
            // $table->foreignId('banco_cheque')->nullable()->references('id')->on('entidads');
            $table->foreignUuid('banco_cheque')->nullable()->references('id')->on('entidads');

            $table->float('importe_cheque', 10, 2)->default(0.0)->nullable();
            $table->string('otro_1')->nullable();
            $table->float('otro_1_importe', 10, 2)->default(0.0)->nullable();
            $table->string('otro_2')->nullable();
            $table->float('otro_2_importe', 10, 2)->default(0.0)->nullable();
            $table->float('total', 10, 2)->default(0.0)->nullable();
            $table->boolean('retencion_afip_cumplida')->default(false);
            $table->boolean('retencion_dgr_cumplida')->default(false);
            $table->string('estado')->nullable();
            $table->boolean('generado')->nullable()->default(false);

            // $table->string('firma')->nullable();
            $table->foreignId('firma_id')->nullable()->references('id')->on('users')->nullable();
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
        Schema::dropIfExists('recibos');
    }
};
