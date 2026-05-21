<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('cliente');
            $table->text('mensaje');
            $table->string('estado')->default('Pendiente');
            $table->string('prioridad')->default('Media');

            // 🔥 RELACIÓN CON NEGOCIO
            $table->unsignedBigInteger('negocio_id');
            $table->foreign('negocio_id')
                  ->references('id')
                  ->on('negocio') // ⚠️ usa el nombre REAL de tu tabla
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};