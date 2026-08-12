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
        Schema::create('minutas', function (Blueprint $table) {
            // $table->id();
            $table->uuid('id')->primary();

            $table->foreignId('producto_id')->constrained('productos');
            $table->unsignedBigInteger('id_original')->nullable();
            $table->unsignedBigInteger('numero')->nullable();
            $table->string('periodo', 8)->nullable();
            $table->date('fecha');
            $table->foreignId('estado_id')->constrained('estados');
            // $table->foreignId('comprador_id')->nullable()->constrained('entidads');
            // $table->foreignId('vendedor_id')->nullable()->constrained('entidads');
            $table->foreignUuid('comprador_id')->nullable()->constrained('entidads');
            $table->foreignUuid('vendedor_id')->nullable()->constrained('entidads');

            $table->foreignId('moneda_id')->nullable()->constrained('monedas');
            $table->foreignId('clase_id')->nullable()->constrained('clases');
            $table->foreignId('valor_id')->nullable()->constrained('valors');

            $table->unsignedBigInteger('importe' )->nullable();
            $table->unsignedBigInteger('tipo_cambio')->nullable();
            $table->unsignedBigInteger('equivalente')->nullable();

            $table->foreignId('referencia_id')->nullable()->constrained('referencias');
            $table->foreignId('tipo_documento_id')->nullable()->constrained('tipo_documentos');
            $table->text('observacion')->nullable();

            $table->float('comision_comprador', 3, 2)->default(0.0)->nullable();
            $table->float('importe_comision_comprador', 10, 2)->default(0.0)->nullable();

            $table->float('comision_vendedor', 3, 2)->default(0.0)->nullable();
            $table->float('importe_comision_vendedor', 10, 2)->default(0.0)->nullable();

            $tipo_operacion = ['Exportación', 'Importación', 'Mercado'];
            $table->enum('operacion', $tipo_operacion)->nullable()->default(null);

            $table->foreignId('factura_cliente_id')->nullable()->references('id')->on('facturas');
            $table->foreignId('factura_banco_id')->nullable()->references('id')->on('facturas');
            // usuario_vendedor_id
            $table->foreignId('usuario_vendedor_id')->nullable()->references('id')->on('users')->nullable();

            // $table->foreignId('entidad_cliente_id')->nullable()->references('id')->on('entidads');
            $table->foreignUuid('entidad_cliente_id')->nullable()->references('id')->on('entidads');

            // $table->string('afip')->nullable();
            $table->boolean('arca')->nullable();
            // bcra_id
            // $table->foreignId('bcra_id')->nullable()->references('id')->on('entidads');
            $table->foreignUuid('bcra_id')->nullable()->references('id')->on('entidads');

            $table->year('anio_desde')->nullable();
            $table->year('anio_hasta')->nullable();
            $table->unsignedInteger('anio_cantidad')->nullable();

            $table->string('periodo_desde', 8)->nullable();
            $table->string('periodo_hasta', 8)->nullable();
            $table->unsignedInteger('periodo_cantidad')->nullable();

            $table->float('importe_comision_unidad', 10, 2)->default(0.0)->nullable();
            $table->float('importe_comision_dolares', 10, 2)->default(0.0)->nullable();
            $table->float('importe_comision', 10, 2)->default(0.0)->nullable();
            $table->unsignedInteger('cantidad')->nullable();

            $table->unsignedInteger('plazo')->nullable()->default(null);
            $table->date('fecha_vencimiento')->nullable()->default(null);

            $table->uuid('padre_id')->nullable()->default(null);
            $table->uuid('hijo_id')->nullable()->default(null);
//usuario_id_created
//created_at
//usuario_id_updated
//updated_at
//usuario_id_deleted
//deleted_at
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minutas');
    }
};
