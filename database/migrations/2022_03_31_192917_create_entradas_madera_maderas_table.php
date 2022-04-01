<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntradasMaderaMaderasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entradas_madera_maderas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entrada_madera_id');
            $table->unsignedBigInteger('madera_id');
            $table->decimal('m3entrada');
            $table->timestamps();

            //llaves foraneas 
            $table->foreign('entrada_madera_id')->references('id')->on('entrada_maderas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('madera_id')->references('id')->on('maderas')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entradas_madera_maderas');
    }
}
