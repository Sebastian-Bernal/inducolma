<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaderaIdToEntradaMaderas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entrada_maderas', function (Blueprint $table) {
            $table->unsignedBigInteger('madera_id');
            $table->foreign('madera_id')->references('id')->on('maderas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entrada_maderas', function (Blueprint $table) {
            $table->dropForeign(['madera_id']);
            $table->dropColumn('madera_id');
        });
    }
}
