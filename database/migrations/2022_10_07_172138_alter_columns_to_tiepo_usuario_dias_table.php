<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnsToTiepoUsuarioDiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tiepo_usuario_dias', function (Blueprint $table) {
            $table->time('entrada')->nullable()->change();
            $table->time('salida')->nullable()->change();
            $table->time('entrada_descanso')->nullable()->change();
            $table->time('salida_descanso')->nullable()->change();
            $table->string('estado')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tiepo_usuario_dias', function (Blueprint $table) {
            //
        });
    }
}
