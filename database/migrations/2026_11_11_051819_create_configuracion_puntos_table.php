<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracion_puntos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idnegocio')->unique();
            $table->integer('puntos_para_canjear');
            $table->decimal('cantidad_de_pesos', 10, 2);
            $table->timestamps();

            $table->foreign('idnegocio')
                ->references('id')
                ->on('negocio')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion_puntos');
    }
};
