<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('verificaciones', function (Blueprint $table) {
            $table->id('id_verificacion');

            // Relación con postulante
            $table->unsignedBigInteger('id_postulante');
            $table->foreign('id_postulante')
                  ->references('id_postulante')
                  ->on('postulantes')
                  ->onDelete('cascade');

            // Datos de verificación
            $table->dateTime('fecha');
            $table->string('placa', 20);
            $table->string('tipo_vehiculo')->nullable();
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();

            // Usuario que realizó la verificación
            $table->unsignedBigInteger('verificado_por');
            $table->foreign('verificado_por')
                  ->references('id') // CORREGIDO: id de la tabla users
                  ->on('users');

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('verificaciones');
    }
};
