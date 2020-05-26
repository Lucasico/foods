<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePessoasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pessoas', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('empresas_id');
            $table->unsignedBigInteger('funcoes_id')->unsigned()->nullable();
            $table->string('nome', 120)->nullable(false);
            $table->string('telefone', 45)->nullable(false);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    //    Schema::dropIfExists('pessoas');
    //    Schema::dropIfExists('empresas');
    //    Schema::dropIfExists('funcoes');
    }
}
