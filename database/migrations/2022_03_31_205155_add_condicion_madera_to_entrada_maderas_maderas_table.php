<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCondicionMaderaToEntradaMaderasMaderasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entradas_madera_maderas', function (Blueprint $table) {
            $table->string('condicion_madera');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entrada_maderas_maderas', function (Blueprint $table) {
            $table->dropColumn('condicion_madera');
        });
    }
}
