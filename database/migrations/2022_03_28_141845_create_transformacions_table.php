<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransformacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transformaciones', function (Blueprint $table) {
            $table->id();
            $table->decimal('ancho');
            $table->decimal('largo');
            $table->decimal('alto');
            $table->string('estado');
            $table->string('trnasformacion_final');
            
            $table->unsignedBigInteger('cubicaje_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('proceso_id');            
            $table->timestamps();
            $table->softDeletes();

            //relaciones con tabla cubicajes, usuaarios y procesos
            $table->foreign('cubicaje_id')->references('id')->on('cubicajes');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('proceso_id')->references('id')->on('procesos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transformacions');
    }
}
