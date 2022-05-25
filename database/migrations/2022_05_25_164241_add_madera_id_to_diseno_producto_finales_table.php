<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaderaIdToDisenoProductoFinalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diseno_producto_finales', function (Blueprint $table) {
            $table->unsignedBigInteger('madera_id');

            // llave foranea a maderas 
            $table->foreign('madera_id')->references('id')->on('maderas');
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
