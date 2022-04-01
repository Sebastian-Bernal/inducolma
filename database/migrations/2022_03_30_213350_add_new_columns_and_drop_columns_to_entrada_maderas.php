<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsAndDropColumnsToEntradaMaderas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entrada_maderas', function (Blueprint $table) {
            $table->dropColumn('mes');
            $table->dropColumn('ano');
            $table->dropColumn('hora');

            
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
            //
        });
    }
}
