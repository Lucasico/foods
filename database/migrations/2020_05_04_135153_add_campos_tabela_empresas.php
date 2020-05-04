<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposTabelaEmpresas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->decimal('taxaEntrega',4,2)->nullable();
            $table->string('tempoEntrega')->nullable();
            $table->string('categoria',30)->nullable(false);
            $table->string('telefone',9)->nullable(false);
            $table->string('celular',20)->nullable(false);
            $table->string('email',100)->nullable();
            $table->string('instagram',45)->nullable();


            //apagar nomeProprietario
            $table->dropColumn('nomeProprietario');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn('taxaEntrega');
            $table->dropColumn('tempoEntrega');
            $table->dropColumn('categoria');
            $table->dropColumn('telefone');
            $table->dropColumn('celular');
            $table->dropColumn('email');
            $table->dropColumn('instagram');

            $table->string('nomeProprietario', 100)->nullable(false);

        });

    }
}
