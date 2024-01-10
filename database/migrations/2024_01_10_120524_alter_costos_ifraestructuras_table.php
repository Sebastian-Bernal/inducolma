<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCostosIfraestructurasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('costos_infraestructuras', function (Blueprint $table) {
            // delete columns
            $table->dropColumn('valor_operativo');
            $table->dropColumn('tipo_material');
            $table->dropColumn('tipo_madera');
            $table->dropColumn('promedio_piezas');
            $table->dropColumn('minimo_piezas');
            $table->dropColumn('maximo_piezas');

        });

        Schema::table('costos_infraestructuras', function (Blueprint $table) {

            // add columns
            $table->float('estandar_u_minuto');
            $table->string('tipo_material');
            $table->unsignedBigInteger('tipo_madera');
            // foreign keys
            $table->foreign('tipo_madera')->references('id')->on('tipo_maderas');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
