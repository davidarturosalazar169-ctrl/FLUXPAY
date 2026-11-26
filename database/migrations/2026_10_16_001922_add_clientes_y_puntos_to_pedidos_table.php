
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->unsignedBigInteger('cliente_negocio_id')
                ->nullable();

            $table->unsignedInteger('puntos_usados')
                ->default(0);

            $table->decimal('descuento_puntos', 10, 2)
                ->default(0);

            $table->foreign('cliente_negocio_id')
                ->references('id')
                ->on('clientes_negocios')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign(['cliente_negocio_id']);

            $table->dropColumn([
                'cliente_negocio_id',
                'puntos_usados',
                'descuento_puntos',
            ]);
        });
    }
};
