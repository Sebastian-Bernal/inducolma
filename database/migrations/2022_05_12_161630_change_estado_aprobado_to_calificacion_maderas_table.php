<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeEstadoAprobadoToCalificacionMaderasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calificacion_maderas', function (Blueprint $table) {
            $table->dropColumn('estado');
            $table->boolean('aprobado')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calificacion_maderas', function (Blueprint $table) {
            $table->dropColumn('aprobado');
            $table->string('estado');
        });
    }
}
