<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('negocio', function (Blueprint $table) {
            $table->string('telefono', 25)->nullable()->after('descripcion');
        });
    }

    public function down(): void
    {
        Schema::table('negocio', function (Blueprint $table) {
            $table->dropColumn('telefono');
        });
    }
};
