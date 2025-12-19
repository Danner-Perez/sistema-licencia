<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('postulantes', function (Blueprint $table) {
            // Quitar índice único del DNI
            $table->dropUnique('postulantes_dni_unique');
        });
    }

    public function down(): void
    {
        Schema::table('postulantes', function (Blueprint $table) {
            // Restaurar índice único
            $table->unique('dni');
        });
    }
};
