<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaderaIdToTransformacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transformaciones', function (Blueprint $table) {
            $table->unsignedBigInteger('madera_id')->nullable();

            $table->foreign('madera_id')->references('id')->on('tipo_maderas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transformaciones', function (Blueprint $table) {
            //
        });
    }
}
