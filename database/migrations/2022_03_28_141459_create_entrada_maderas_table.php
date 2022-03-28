<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntradaMaderasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entrada_maderas', function (Blueprint $table) {
            $table->id();
            $table->date('mes');
            $table->date('ano');
            $table->time('hora');
            $table->date('fecha');
            $table->string('acto_administrativo');
            $table->integer('salvoconducto_remision');
            $table->string('titular_salvoconducto');            
            $table->string('procedencia_madera');
            $table->string('nombre_madera');
            $table->string('nombre_cientifico');
            $table->string('entidad_vigilante');           
            $table->string('tipo_madera');
            $table->string('vitacora');
            $table->string('condicion_madera');
            $table->decimal('m3_entrada');
            $table->string('estado');
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('proveedor_id');
            $table->unsignedBigInteger('user_id');
            //relaciones con tablas user y proveedores
            
            $table->foreign('user_id')->references('id')->on('users');
            
            $table->foreign('proveedor_id')->references('id')->on('proveedores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entrada_maderas');
    }
}
