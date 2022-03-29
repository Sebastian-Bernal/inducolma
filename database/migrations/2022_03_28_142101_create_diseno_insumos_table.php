<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisenoInsumosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diseno_insumos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('diseno_id');
            $table->unsignedBigInteger('insumo_id');
            $table->timestamps();

            //relaciones con tabla disenos, insumos
            $table->foreign('diseno_id')->references('id')->on('diseno_producto_finales');
            $table->foreign('insumo_id')->references('id')->on('insumos_almacen');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diseno_insumos');
    }
}
