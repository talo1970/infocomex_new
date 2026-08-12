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
        Schema::create('contactos', function (Blueprint $table) {
            $table->id();
            //$table->foreignUuid('judicial_id')->references('id')->on('judiciales');

            $table->foreignUuid('entidad_id')->constrained();
            $table->string('contacto', 150);
            $table->string('mail', 150)->nullable();
            $table->string('telefono', 150)->nullable();
            $table->string('domicilio', 150)->nullable();
            $table->string('numero', 50)->nullable();
            $table->string('departamento_piso', 150)->nullable();
            $table->string('codigo_postal', 150)->nullable();
            $table->string('localidad', 150)->nullable();
            $table->foreignId('provincia_id')->nullable()->constrained();
            $table->string('pais', 100)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contactos');
    }
};
