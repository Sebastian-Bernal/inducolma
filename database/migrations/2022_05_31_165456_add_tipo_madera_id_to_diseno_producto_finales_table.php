<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoMaderaIdToDisenoProductoFinalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diseno_producto_finales', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_madera_id');
            $table->foreign('tipo_madera_id')->references('id')->on('tipo_maderas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('diseno_producto_finales', function (Blueprint $table) {
            //
        });
    }
}
