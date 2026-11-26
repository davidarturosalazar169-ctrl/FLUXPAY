<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos_apikey', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('api_key_negocio_id');

            $table->string('negocio_externo_id', 100);

            $table->string('referencia_externa', 100);

            $table->decimal('monto', 10, 2);

            $table->string('stripe_payment_intent')->nullable();

            $table->string('status')->default('pendiente');

            $table->timestamps();

            $table->index('api_key_negocio_id');
            $table->index('negocio_externo_id');
            $table->index('referencia_externa');
            $table->index('stripe_payment_intent');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos_apikey');
    }
};
