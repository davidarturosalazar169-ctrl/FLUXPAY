<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_keys_negocios', function (Blueprint $table) {
            $table->id();

            $table->string('negocio_externo_id', 100);
            $table->string('nombre', 100);

            $table->string('api_key_hash', 64)->unique();

            $table->boolean('activa')->default(true);
            $table->timestamp('ultimo_uso')->nullable();

            $table->timestamps();

            $table->index('negocio_externo_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_keys_negocios');
    }
};
