<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostosOperacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('costos_operacion', function (Blueprint $table) {
            $table->id();
            $table->float('cantidad');
            $table->float('valor_mes');
            $table->float('valor_dia');
            $table->float('costo_kwh');
            $table->integer('maquina_id')->unsigned();
            $table->integer('descripcion_id')->unsigned();
            $table->foreign('maquina_id')->references('id')->on('maquinas');
            $table->foreign('descripcion_id')->references('id')->on('descripciones');
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
        Schema::dropIfExists('costos_operacion');
    }
}
