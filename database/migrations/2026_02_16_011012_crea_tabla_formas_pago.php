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
        Schema::create('formas_pago', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->unsignedBigInteger('creado_por');
            $table->string('nombre', 80);
            $table->string('descripcion', 150)->nullable();
            $table->tinyInteger('estatus')->unsigned()->default(1);
            $table->timestamps();
            $table->comment('Catálogo de formas de pago');

            $table->unique(['creado_por', 'nombre']);
            $table->foreign('creado_por')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formas_pago');
    }
};
