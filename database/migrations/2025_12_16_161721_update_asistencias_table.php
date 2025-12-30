<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        /*
        |----------------------------------------------------------------------
        | POSTULANTES
        |----------------------------------------------------------------------
        */
        Schema::create('postulantes', function (Blueprint $table) {
            $table->id('id_postulante');
            $table->string('dni', 8); // SIN UNIQUE
            $table->string('nombres');
            $table->string('apellidos');
            $table->date('fecha_psicosomatico');
            $table->boolean('validado_reniec')->default(false);
            $table->unsignedBigInteger('registrado_por');
            $table->timestamps();

            $table->foreign('registrado_por')
                  ->references('id')
                  ->on('users');
        });

        /*
        |----------------------------------------------------------------------
        | PROCESOS DE LICENCIA
        |----------------------------------------------------------------------
        */
        Schema::create('procesos_licencia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('postulante_id');
            $table->string('tipo_licencia', 20);
            $table->date('fecha_inicio');

            $table->enum('estado', ['EN_PROCESO', 'APROBADO', 'ANULADO'])
                  ->default('EN_PROCESO');

            $table->enum('tipo_tramite', ['OBTENCIÓN', 'RECATEGORIZACIÓN'])
                  ->default('OBTENCIÓN');

            $table->timestamps();

            $table->foreign('postulante_id')
                  ->references('id_postulante')
                  ->on('postulantes')
                  ->onDelete('cascade');
        });

        /*
        |----------------------------------------------------------------------
        | INTENTOS DE EXAMEN
        |----------------------------------------------------------------------
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

        /*
        |----------------------------------------------------------------------
        | EXÁMENES
        |----------------------------------------------------------------------
        */
        Schema::create('examenes', function (Blueprint $table) {
            $table->id('id_examen');
            $table->unsignedBigInteger('id_postulante');
            $table->date('fecha');
            $table->enum('resultado', ['APROBADO','NO APROBADO']);
            $table->string('observacion')->nullable();
            $table->timestamps();

            $table->foreign('id_postulante')
                  ->references('id_postulante')
                  ->on('postulantes')
                  ->onDelete('cascade');
        });

        /*
        |----------------------------------------------------------------------
        | VERIFICACIONES
        |----------------------------------------------------------------------
        */
        Schema::create('verificaciones', function (Blueprint $table) {
            $table->id('id_verificacion');
            $table->unsignedBigInteger('id_postulante');
            $table->dateTime('fecha');
            $table->string('placa', 20);
            $table->string('tipo_vehiculo')->nullable();
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->unsignedBigInteger('verificado_por');
            $table->timestamps();

            $table->foreign('id_postulante')
                  ->references('id_postulante')
                  ->on('postulantes')
                  ->onDelete('cascade');

            $table->foreign('verificado_por')
                  ->references('id')
                  ->on('users');
        });

        /*
        |----------------------------------------------------------------------
        | ASISTENCIAS
        |----------------------------------------------------------------------
        */
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id('id_asistencia');
            $table->unsignedBigInteger('postulante_id');
            $table->date('fecha');
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
        Schema::dropIfExists('verificaciones');
        Schema::dropIfExists('examenes');
        Schema::dropIfExists('intentos_examen');
        Schema::dropIfExists('procesos_licencia');
        Schema::dropIfExists('postulantes');
    }
};
