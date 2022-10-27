<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropNotNullColumnsToSubprocesosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subprocesos', function (Blueprint $table) {
            $table->string('observacion_subproceso')->nullable()->change();
            $table->decimal('tiempo')->nullable()->change();
            $table->time('hora_inicio')->nullable()->change();
            $table->time('hora_fin')->nullable()->change();
            $table->integer('cantidad_entrada')->nullable()->change();
            $table->integer('cantidad_salida')->nullable()->change();
            $table->dateTime('fecha_ejecucion')->nullable()->change();
            $table->integer('sub_paqueta')->nullable()->change();
            $table->string('tarjeta_entrada')->nullable()->change();
            $table->string('tarjeta_salida')->nullable()->change();
            $table->integer('sobrante')->nullable()->change();
            $table->integer('lena')->nullable()->change();


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
