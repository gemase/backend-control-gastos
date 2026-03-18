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
        Schema::create('presupuestos', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->unsignedBigInteger('creado_por');
            $table->unsignedBigInteger('id_periodo');
            $table->unsignedBigInteger('id_categoria')->nullable();
            $table->decimal('monto', 18, 2)->unsigned();
            $table->timestamps();
            $table->comment('Catálogo de presupuestos');

            $table->unique(['creado_por', 'id_periodo', 'id_categoria']);

            $table->foreign('creado_por')->references('id')->on('users');
            $table->foreign('id_periodo')->references('id')->on('periodos');
            $table->foreign('id_categoria')->references('id')->on('categorias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presupuestos');
    }
};
