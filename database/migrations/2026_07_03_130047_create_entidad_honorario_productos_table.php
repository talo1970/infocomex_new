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
        Schema::create('entidad_honorario_productos', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('entidad_id')->constrained('entidads');
            $table->foreignUuid('entidad_id')->constrained('entidads');
            $table->foreignId('honorario_producto_id')->constrained('honorario_productos');
            // $table->unsignedInteger('honorario_id');
            // $table->foreignId('producto_id')->constrained('productos');
            // $table->decimal('importe', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entidad_honorario_productos');
    }
};
