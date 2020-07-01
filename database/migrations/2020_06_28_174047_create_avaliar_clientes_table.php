<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvaliarClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avaliar_clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('users_id');
            $table->uuid('empresa_id');
            $table->char('situacao_cliente',1)->nullable(false);

            $table->timestamps();

            $table->foreign('users_id')->references('id')->on('users');
            $table->foreign('empresa_id')->references('id')->on('empresas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avaliar_clientes');
    }
}
