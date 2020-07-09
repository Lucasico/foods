<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserTelefoneEFuncionamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('telefone',45)->change()->nullable(true);
            $table->string('rua',100)->change()->nullable(true);
            $table->string('bairro',100)->change()->nullable(true);
            $table->string('numero',45)->change()->nullable(true);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['telefone','rua','bairro','numero']);
           // $table->string('telefone',45)->nullable(false);
        });
    }
}
