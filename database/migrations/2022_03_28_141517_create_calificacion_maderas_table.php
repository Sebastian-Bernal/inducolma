<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalificacionMaderasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calificacion_maderas', function (Blueprint $table) {
            $table->id();
            $table->decimal('longitud_madera');
            $table->decimal('largo_madera');
            $table->decimal('cantonera');
            $table->decimal('hongos');
            $table->decimal('rajadura');
            $table->decimal('bichos');
            $table->decimal('organizacion');
            $table->decimal('areas_transversal_max_min');
            $table->decimal('areas_no_conveniente');
            $table->decimal('total');
            $table->string('estado');
            $table->timestamps();
            $table->softDeletes();

            //relaciones con tabla entradas_maderas
            $table->unsignedBigInteger('entrada_madera_id');
            $table->foreign('entrada_madera_id')->references('id')->on('entrada_maderas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calificacion_maderas');
    }
}
