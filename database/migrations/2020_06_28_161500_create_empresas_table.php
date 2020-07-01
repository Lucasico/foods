<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->unsignedBigInteger('cidade_id')->unsigned()->nullable();
            $table->string('razao_social',45)->nullable(false);
            $table->string('cnpj',18)->nullable(false);
            $table->char('situacao',1)->nullable(false);
            $table->string('bairro',45)->nullable(false);
            $table->string('rua',45)->nullable(false);
            $table->string('cep',20)->nullable(true);
            $table->decimal('taxaEntrega',4,2)->nullable(false);
            $table->string('tempoEntrega',45)->nullable(true);
            $table->string('categoria','45')->nullable(false);
            $table->string('telefone',9)->nullable(false);
            $table->string('celular',20)->nullable(true);
            $table->string('email',45)->nullable(true);
            $table->string('instagram',45)->nullable(true);
            $table->string('numero',25)->nullable(false);

            $table->foreign('cidade_id')->references('id')->on('cidades');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresas');
    }
}
