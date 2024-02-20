<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCantidadProducidaToEnsamblesAcabados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ensambles_acabados', function (Blueprint $table) {
            $table->integer('cantidad_producida')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ensambles_acabados', function (Blueprint $table) {
            $table->dropColumn('cantidad_producida');  // Eliminar la columna 'cantidad_producida' de la tabla 'ensambles_acabados'
        });
    }
}
