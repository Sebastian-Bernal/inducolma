<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisenoProductoFinalInsumosTable extends Migration
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
            $table->unsignedBigInteger('diseno_producto_final_id');
            $table->unsignedBigInteger('insumo_almacen_id');
            $table->integer('cantidad');
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
