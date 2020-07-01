<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
           // $table->uuid('pessoas_id');
            $table->unsignedBigInteger('permissao_id')->unsigned()->nullable();
            $table->unsignedBigInteger('cidade_id')->unsigned()->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('nome',100)->nullable(false);
            $table->string('telefone',45)->nullable(false);
            $table->string('rua',100)->nullable(false);
            $table->string('bairro',100)->nullable(false);
            $table->string('numero',45)->nullable(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('users');
    }
}
