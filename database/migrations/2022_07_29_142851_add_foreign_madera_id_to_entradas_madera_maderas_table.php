<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignMaderaIdToEntradasMaderaMaderasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entradas_madera_maderas', function (Blueprint $table) {
            $table->foreign('madera_id')->references('id')->on('maderas')->onUpdate('CASCADE');        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entradas_madera_maderas', function (Blueprint $table) {
            //
        });
    }
}
