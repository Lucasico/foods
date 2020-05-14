<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->unsignedBigInteger('tipos_id');
            $table->uuid('empresas_id');
            $table->string('nome', 45)->nullable(false);
            $table->string('unidade_compra', 10)->nullable(false);
            $table->string('descricao', 255)->nullable();
            $table->double('precoVenda')->nullable(false);
            $table->double('precoCompra')->nullable(false);
            $table->integer('quantMinina')->nullable(false);
            $table->integer('quantEstoque')->nullable(false);
            $table->timestamps();
            $table->foreign('tipos_id')->references('id')->on('tipos');
            $table->foreign('empresas_id')->references('id')->on('empresas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       // Schema::dropIfExists('produtos');
    }
}
