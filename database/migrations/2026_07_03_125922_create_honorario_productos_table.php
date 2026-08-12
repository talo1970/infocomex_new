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
        Schema::create('honorario_productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('honorario_id');
            $table->foreignId('producto_id')->constrained('productos');
            $table->float('importe', 10, 2)->default(0.0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('honorario_productos');
    }
};
