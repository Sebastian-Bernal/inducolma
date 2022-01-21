<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostosInfraestructurasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('costos_infraestructuras', function (Blueprint $table) {
            $table->id();
            $table->float('valor_operativo');
            $table->string('tipo_material');
            $table->string('tipo_madera');
            $table->string('proceso_madera');
            $table->float('promedio_piezas');
            $table->float('minimo_piezas');
            $table->float('maximo_piezas');
            $table->integer('maquina_id')->unsigned();
            $table->foreign('maquina_id')->references('id')->on('maquinas');
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('costos_infraestructuras');
    }
}
