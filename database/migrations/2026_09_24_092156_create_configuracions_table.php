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
        Schema::create('configuracions', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->string('valor_texto')->nullable();
            $table->bigInteger('valor_numerico')->nullable();
            $table->decimal('valor_importe', 10, 2)->nullable();
            $table->date('valor_fecha')->nullable();
            $table->dateTime('valor_datetime')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracions');
    }
};
