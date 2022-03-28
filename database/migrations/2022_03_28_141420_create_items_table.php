<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            $table->decimal('alto');
            $table->decimal('ancho');
            $table->decimal('largo');
            $table->integer('existencias');
            $table->string('tipo_madera');
            $table->integer('codigo_cg');
            $table->boolean('preprocesado')->default(false);
            $table->integer('carretos');
            $table->datetime('fecha_ingreso');
            $table->datetime('fecha_uso');
            $table->string('estado');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
