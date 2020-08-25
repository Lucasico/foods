<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProdutoPertenceEstoqueUnidCompra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('produtos', function (Blueprint $table) {
            //$table->dropColumn(['pertence_estoque', 'unidade_compra']);
            $table->char('pertence_estoque',1)->nullable(true);
            $table->string('unidade_compra',20)->nullable(true);
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropColumn(['pertence_estoque', 'unidade_compra']);
        });
    }
}
