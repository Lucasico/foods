<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComposicaoProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('composicao_produtos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('produtos_id');
            $table->unsignedBigInteger('composicao_id');
            $table->foreign('produtos_id')->references('id')->on('produtos');
            $table->foreign('composicao_id')->references('id')->on('composicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
     //   Schema::dropIfExists('composicao_produtos');
    }
}
