<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSobranteTrozasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sobrante_trozas', function (Blueprint $table) {
            $table->id();
            $table->decimal('ancho')->nullable();
            $table->decimal('largo')->nullable();
            $table->decimal('alto')->nullable();
            $table->string('estado')->default('DISPONIBLE');
            $table->bigInteger('cubicaje_id');
            $table->bigInteger('madera_id');
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
        Schema::dropIfExists('sobrante_trozas');
    }
}
