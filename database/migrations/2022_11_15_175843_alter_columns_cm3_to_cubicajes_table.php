<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnsCm3ToCubicajesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cubicajes', function (Blueprint $table) {
            $table->decimal('pulgadas_cuadradas',22, 2)->nullable()->default(0)->change();
            $table->decimal('pulgadas_cuadradas_x3_metros', 22, 2)->nullable()->default(0)->change();
            $table->decimal('diametro_mayor',22, 2)->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cubicajes', function (Blueprint $table) {
            //
        });
    }
}
