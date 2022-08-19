<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToTransformacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transformaciones', function (Blueprint $table) {
            $table->decimal('sobrante');
            $table->decimal('desperdicio');
            $table->decimal('cantidad');
            $table->string('tipo_corte');
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
