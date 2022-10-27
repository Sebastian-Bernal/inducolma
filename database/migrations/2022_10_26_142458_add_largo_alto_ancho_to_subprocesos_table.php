<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLargoAltoAnchoToSubprocesosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subprocesos', function (Blueprint $table) {
            $table->decimal('largo',12, 2)->nullable();
            $table->decimal('alto',12, 2)->nullable();
            $table->decimal('ancho',12, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subprocesos', function (Blueprint $table) {
            //
        });
    }
}
