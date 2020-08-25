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
            $table->uuid('empresa_id');
            $table->unsignedBigInteger('sub_categoria_id')->unsigned()->nullable();
            $table->char('situacao',1)->nullable(false);
            $table->double('preco')->nullable(false);
            $table->char('tipo',1)->nullable(false);
            //$table->char('pertence_estoque',1)->nullable(false);
            $table->string('tamanho',45)->nullable(false);
            //$table->string('unidade_compra',20)->nullable(false);
            $table->text('descricao')->nullable(false);
            $table->integer('quantMinima')->nullable(true);
            $table->integer('quantEstoque')->nullable(true);


            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtos');
    }
}
