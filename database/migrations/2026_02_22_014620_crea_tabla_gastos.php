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
        Schema::create('gastos', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->unsignedBigInteger('creado_por');
            $table->date('fecha');
            $table->decimal('monto', 18, 2)->unsigned();
            $table->unsignedBigInteger('id_categoria');
            $table->unsignedBigInteger('id_forma_pago');
            $table->string('descripcion', 80);
            $table->tinyInteger('estatus')->unsigned()->default(1);
            $table->unsignedBigInteger('id_periodo')->nullable();
            $table->timestamps();
            $table->index('fecha');
            $table->index('estatus');
            $table->comment('Catálogo de gastos');

            $table->foreign('creado_por')->references('id')->on('users');
            $table->foreign('id_categoria')->references('id')->on('categorias');
            $table->foreign('id_forma_pago')->references('id')->on('formas_pago');
            $table->foreign('id_periodo')->references('id')->on('periodos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gastos');
    }
};
