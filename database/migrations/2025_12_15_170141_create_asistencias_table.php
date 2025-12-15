<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id('id_asistencia');

            $table->unsignedBigInteger('id_postulante');
            $table->date('fecha');
            $table->time('hora_llegada');
            $table->time('hora_salida')->nullable();
            $table->string('observacion')->nullable();

            $table->unsignedBigInteger('registrado_por');

            $table->foreign('id_postulante')
                  ->references('id_postulante')
                  ->on('postulantes');

            $table->foreign('registrado_por')
                  ->references('id_usuario')
                  ->on('usuarios');

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('asistencias');
    }
};
