<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnsamblesAcabadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ensambles_acabados', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->text('observaciones')->nullable();
            $table->dateTime('fecha_inicio')->nullable();
            $table->dateTime('fecha_fin')->nullable();
            $table->string('estado')->nullable();
            $table->unsignedBigInteger('pedido_id');
            $table->unsignedBigInteger('maquina_id');
            $table->unsignedBigInteger('user_id');

            $table->timestamps();

            $table->foreign('pedido_id')->references('id')->on('pedidos')->onDelete('CASCADE');
            $table->foreign('maquina_id')->references('id')->on('maquinas');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ensambles_acabados');
    }
}
