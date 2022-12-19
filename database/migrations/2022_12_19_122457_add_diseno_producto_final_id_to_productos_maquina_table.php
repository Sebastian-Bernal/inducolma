<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDisenoProductoFinalIdToProductosMaquinaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('productos_maquina', function (Blueprint $table) {
            $table->unsignedBigInteger('diseno_producto_final_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('productos_maquina', function (Blueprint $table) {
            $table->dropColumn('diseno_producto_final_id');
        });
    }
}
