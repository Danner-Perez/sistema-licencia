<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('procesos_licencia', function (Blueprint $table) {
            $table->enum('tipo_tramite', ['OBTENCIÓN', 'RECATEGORIZACIÓN'])->default('OBTENCIÓN')
                ->default('OBTENCIÓN')
                ->after('estado'); // coloca después de 'estado'
        });
    }

    public function down()
    {
        Schema::table('procesos_licencia', function (Blueprint $table) {
            $table->dropColumn('tipo_tramite');
        });
    }


    /**
     * Reverse the migrations.
     */
    
};
