<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenProduccionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordenes_produccion', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->string('estado');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('pedido_id');
            $table->unsignedBigInteger('diseno_producto_final_id');
            $table->timestamps();
            $table->softDeletes();
            // Relaciones con tabla pedidos, diseños de producto final y usuarios
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('pedido_id')->references('id')->on('pedidos');
            $table->foreign('diseno_producto_final_id')->references('id')->on('diseno_producto_finales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orden_produccions');
    }
}
