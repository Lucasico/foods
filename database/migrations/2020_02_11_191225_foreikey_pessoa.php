<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForeikeyPessoa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pessoas', function (Blueprint $table) {
            $table->foreign('empresas_id')->references('id')->on('empresas');
            $table->foreign('funcoes_id')->references('id')->on('funcoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pessoas',function(Blueprint $table){
          //  Schema::dropIfExists('empresas_id');
          //  Schema::dropIfExists('funcoes_id');

        });
    }
}
