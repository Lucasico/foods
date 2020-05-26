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
            $table->string('razao_social', 45)->nullable(false);
            $table->string('nomeProprietario', 100)->nullable(false);
            $table->string('cnpj', 18)->nullable(false);
            $table->boolean('situacao')->nullable(false);
            $table->string('bairro', 45)->nullable(false);
            $table->string('rua', 45)->nullable(false);
            $table->string('cep', 20)->nullable(false);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       // Schema::dropIfExists('empresas');
    }
}
