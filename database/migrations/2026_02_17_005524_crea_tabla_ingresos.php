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
        Schema::create('ingresos', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->unsignedBigInteger('creado_por');
            $table->unsignedBigInteger('id_periodo')->nullable();
            $table->date('fecha');
            $table->decimal('monto', 18, 2);
            $table->string('nombre', 80);
            $table->tinyInteger('estatus')->unsigned()->default(1);
            $table->timestamps();
            $table->comment('Catálogo de ingresos');

            $table->foreign('creado_por')->references('id')->on('users');
            $table->foreign('id_periodo')->references('id')->on('periodos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingresos');
    }
};
