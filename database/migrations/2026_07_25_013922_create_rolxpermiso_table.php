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
        Schema::create('rolxpermiso', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('idrol');
            $table->unsignedBigInteger('idpermiso');

            $table->foreign('idrol')
                ->references('id')
                ->on('rol')
                ->onDelete('cascade');

            $table->foreign('idpermiso')
                ->references('id')
                ->on('permiso')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rolxpermiso');
    }
};