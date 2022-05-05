<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnTipoMaderaToCostosInfreaestructurasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('costos_infraestructuras', function (Blueprint $table) {
            $table->dropColumn('tipo_madera');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('costos_infraestructuras', function (Blueprint $table) {
            //
        });
    }
}
