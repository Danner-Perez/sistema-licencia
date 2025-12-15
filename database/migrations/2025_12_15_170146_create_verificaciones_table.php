<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('verificaciones', function (Blueprint $table) {
            $table->id('id_verificacion');

            $table->unsignedBigInteger('id_postulante');
            $table->dateTime('fecha');
            $table->string('placa', 20);
            $table->string('tipo_vehiculo')->nullable();
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();

            $table->unsignedBigInteger('verificado_por');

            $table->foreign('id_postulante')
                  ->references('id_postulante')
                  ->on('postulantes');

            $table->foreign('verificado_por')
                  ->references('id_usuario')
                  ->on('usuarios');

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('verificaciones');
    }
};
