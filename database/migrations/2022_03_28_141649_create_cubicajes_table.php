<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCubicajesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cubicajes', function (Blueprint $table) {
            $table->id();
            $table->integer('paqueta');
            $table->decimal('largo');
            $table->decimal('ancho');
            $table->decimal('alto');
            $table->decimal('cm3');
            $table->decimal('pulgadas_cuadradas');
            $table->decimal('pulgadas_cuadradas_x3_metros');
            $table->decimal('diametro');
            $table->decimal('bloque');
            $table->decimal('pulgadas_ancho');
            $table->decimal('pulgadas_alto');
            $table->string('estado');
            $table->timestamps();
            $table->softDeletes();

            //relaciones con tabla entradas_maderas y usuarios
            $table->unsignedBigInteger('entrada_madera_id');
            $table->foreign('entrada_madera_id')->references('id')->on('entrada_maderas');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cubicajes');
    }
}
