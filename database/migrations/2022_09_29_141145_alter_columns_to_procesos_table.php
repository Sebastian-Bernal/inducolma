<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnsToProcesosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procesos', function (Blueprint $table) {
            $table->string('observacion')->nullable()->change();
            $table->integer('tiempo')->nullable()->change();
            $table->time('hora_inicio')->nullable()->change();
            $table->time('hora_fin')->nullable()->change();
            $table->decimal('cm3_entrada', 20,2)->nullable()->change();
            $table->decimal('cm3_salida',20,2)->nullable()->change();
            $table->date('fecha_ejecucion')->nullable()->change();
            $table->integer('cantidad_items')->nullable()->change();
            $table->integer('sub_paqueta')->nullable()->change();
            $table->date('fecha_finalizacion')->nullable()->change();
            $table->renameColumn('maquinas_id', 'maquina_id');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procesos', function (Blueprint $table) {

        });
    }
}
