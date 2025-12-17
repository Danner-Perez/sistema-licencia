<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | POSTULANTES (PERSONA)
        |--------------------------------------------------------------------------
        */
        Schema::create('postulantes', function (Blueprint $table) {
            $table->id('id_postulante');
            $table->string('dni', 8)->unique();
            $table->string('nombres');
            $table->string('apellidos');

            // 👉 PSICOSOMÁTICO (6 meses de vigencia)
            $table->date('fecha_psicosomatico');

            $table->boolean('validado_reniec')->default(false);
            $table->unsignedBigInteger('registrado_por');
            $table->timestamps();

            $table->foreign('registrado_por')
                  ->references('id')
                  ->on('users');
        });

        /*
        |--------------------------------------------------------------------------
        | PROCESOS DE LICENCIA (TRÁMITE)
        |--------------------------------------------------------------------------
        */
        Schema::create('procesos_licencia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('postulante_id');
            $table->string('tipo_licencia', 20); // A1, A2B, etc.
            $table->date('fecha_inicio');

            $table->enum('estado', ['EN_PROCESO', 'APROBADO', 'ANULADO'])
                  ->default('EN_PROCESO');

            $table->timestamps();

            $table->foreign('postulante_id')
                  ->references('id_postulante')
                  ->on('postulantes')
                  ->onDelete('cascade');
        });

        /*
        |--------------------------------------------------------------------------
        | INTENTOS DE EXAMEN
        |--------------------------------------------------------------------------
        */
        Schema::create('intentos_examen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proceso_licencia_id');
            $table->unsignedTinyInteger('numero_intento');
            $table->date('fecha_intento');
            $table->enum('resultado', ['APROBADO', 'DESAPROBADO']);
            $table->timestamps();

            $table->foreign('proceso_licencia_id')
                  ->references('id')
                  ->on('procesos_licencia')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intentos_examen');
        Schema::dropIfExists('procesos_licencia');
        Schema::dropIfExists('postulantes');
    }
};
