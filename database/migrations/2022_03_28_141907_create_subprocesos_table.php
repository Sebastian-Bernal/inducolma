<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubprocesosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subprocesos', function (Blueprint $table) {
            $table->id();
            $table->integer('paqueta');
            $table->string('observacion_subproceso');
            $table->decimal('tiempo');
            $table->string('entrada');
            $table->string('salida');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->integer('cantidad_entrada');
            $table->integer('cantidad_salida');
            $table->date('fecha_ejecucion');            
            $table->integer('sub_paqueta');
            $table->string('tarjeta_entrada');
            $table->string('tarjeta_salida');
            $table->integer('sobrante');
            $table->integer('lena');
            $table->string('estado');
            $table->unsignedBigInteger('proceso_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->softDeletes();
            
            //relaciones con tabla procesos, usuarios 
            $table->foreign('proceso_id')->references('id')->on('procesos');   
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
        Schema::dropIfExists('subprocesos');
    }
}
