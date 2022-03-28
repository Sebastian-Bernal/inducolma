<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiepoUsuarioDiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tiepo_usuario_dias', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->time('entrada');
            $table->time('salida');
            $table->time('entrada_descanso');
            $table->time('salida_descanso');
            $table->string('estado');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('maquina_id');
            $table->timestamps();
            $table->softDeletes();
            // Relaciones 018000912345 #263
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->foreign('maquina_id')->references('id')->on('maquinas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tiepo_usuario_dias');
    }
}
