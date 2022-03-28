<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisenoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diseno_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('diseno_id');
            $table->unsignedBigInteger('item_id');
            $table->timestamps();

            //relaciones con tabla disenos, items
            $table->foreign('diseno_id')->references('id')->on('disenos_productos_finales');
            $table->foreign('item_id')->references('id')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diseno_items');
    }
}
