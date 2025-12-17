<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id('id_asistencia');

            $table->unsignedBigInteger('postulante_id');
            $table->date('fecha'); // SIN default
            $table->time('hora_llegada');
            $table->time('hora_salida')->nullable();
            $table->string('observacion')->nullable();
            $table->unsignedBigInteger('registrado_por');

            $table->timestamps();

            $table->foreign('postulante_id')
                  ->references('id_postulante')
                  ->on('postulantes')
                  ->onDelete('cascade');

            $table->foreign('registrado_por')
                  ->references('id')
                  ->on('users')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
