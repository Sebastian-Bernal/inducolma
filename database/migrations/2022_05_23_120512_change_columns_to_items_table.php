<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->integer('existencias')->nullable()->change();
            $table->boolean('preprocesado')->nullable()->change();
            $table->integer('carretos')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->integer('existencias')->nullable(false)->change();
            $table->boolean('preprocesado')->nullable(false)->change();
            $table->integer('carretos')->nullable(false)->change();
        });
    }
}
