<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaderaIdToTurnoUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('turno_usuarios', function (Blueprint $table) {
            $table->unsignedBigInteger('maquina_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('turno_usuarios', function (Blueprint $table) {
            $table->dropColumn('maquina_id');
        });
    }
}
