<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropLargoMaderaToCalificacionMaderasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calificacion_maderas', function (Blueprint $table) {
            $table->dropColumn('largo_madera');
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
            $table->decimal('largo_madera');
        
        });
    }
}
