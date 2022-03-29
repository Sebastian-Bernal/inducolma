<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcesosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procesos', function (Blueprint $table) {
            $table->id();
            $table->string('proceso');
            $table->string('observaion_proceso');
            $table->decimal('tiempo');
            $table->string('entrada');
            $table->string('salida');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->integer('cantidad_entrada');
            $table->integer('cantidad_salida');
            $table->date('fecha_ejecucion');
            $table->integer('numero_bloque');
            $table->integer('sub_paqueta');
            $table->date('fecha_finalizacion');
            $table->string('estado');
            $table->integer('paqueta');
            $table->unsignedBigInteger('items_id');
            $table->unsignedBigInteger('orden_produccion_id');
            $table->unsignedBigInteger('maquinas_id');

            $table->timestamps();
            $table->softDeletes();

            // Relaciones con tabla items, ordenes de produccion, items y maquinas
            $table->foreign('items_id')->references('id')->on('items');
            $table->foreign('orden_produccion_id')->references('id')->on('ordenes_produccion');
            $table->foreign('maquinas_id')->references('id')->on('maquinas');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('procesos');
    }
}
