<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('postulantes', function (Blueprint $table) {
            $table->id('id_postulante');
            $table->string('dni', 8)->unique();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('tipo_licencia', 20);
            $table->date('fecha_registro');
            $table->boolean('validado_reniec')->default(false);

            $table->unsignedBigInteger('registrado_por');
            $table->foreign('registrado_por')
                ->references('id')
                ->on('users');


            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('postulantes');
    }
};
