<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnEntradaMaderaIdToCalificacionMaderasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calificacion_maderas', function (Blueprint $table) {
            $table->unsignedBigInteger('entrada_madera_madera_id')->nullable();
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
            //
        });
    }
}
