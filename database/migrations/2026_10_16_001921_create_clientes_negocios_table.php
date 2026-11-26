
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes_negocios', function (Blueprint $table) {
            $table->id();
            $table->string('nombreCliente');
            $table->unsignedBigInteger('idnegocio');
            $table->string('telefono', 20)->nullable();
            $table->unsignedInteger('puntos')->default(0);
            $table->timestamps();

$table->foreign('idnegocio')
    ->references('id')
    ->on('negocio')
    ->cascadeOnDelete();

            $table->index(['idnegocio', 'nombreCliente']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes_negocios');
    }
};
