<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {

            $table->uuid('id');
            $table->primary('id');
            $table->unsignedBigInteger('situacao_pedido_id')->unsigned()->nullable();
            $table->unsignedBigInteger('forma_pagamento_id')->unsigned()->nullable();
            $table->uuid('user_id');
            $table->bigInteger('codigo')->nullable(false);
            $table->string('observacoes',255)->nullable(true);
            $table->timestamps();

            $table->foreign('situacao_pedido_id')->references('id')->on('situacao_pedidos');
            $table->foreign('forma_pagamento_id')->references('id')->on('formas_pagamentos');
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
        Schema::dropIfExists('pedidos');
    }
}
