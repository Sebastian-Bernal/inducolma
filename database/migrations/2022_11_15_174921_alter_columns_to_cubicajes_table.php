<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnsToCubicajesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cubicajes', function (Blueprint $table) {
            $table->decimal('largo')->nullable()->default(0)->change();
            $table->decimal('ancho')->nullable()->default(0)->change();
            $table->decimal('alto')->nullable()->default(0)->change();
            $table->decimal('pulgadas_cuadradas')->nullable()->default(0)->change();
            $table->decimal('pulgadas_cuadradas_x3_metros')->nullable()->default(0)->change();
            $table->renameColumn('diametro','diametro_mayor')->nullable()->default(0)->change();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cubicajes', function (Blueprint $table) {
            //
        });
    }
}
