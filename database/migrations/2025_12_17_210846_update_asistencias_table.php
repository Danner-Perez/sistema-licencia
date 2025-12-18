<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('examenes', function (Blueprint $table) {
            $table->id('id_examen');

            $table->unsignedBigInteger('id_postulante');
            $table->date('fecha');
            $table->enum('resultado', ['APROBADO','NO APROBADO']);
            $table->string('observacion')->nullable();

            $table->foreign('id_postulante')
                ->references('id_postulante')
                ->on('postulantes');

            $table->timestamps();
        });

    }

    public function down(): void {
        Schema::dropIfExists('examenes');
    }
};
