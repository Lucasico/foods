<?php

namespace App\Http\Controllers\modulos\proprietario\produtos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use App\Pessoas;
use App\Produtos;
use App\Empresas;
use App\Tipos;

class ProdutoCrudController extends Controller
{
    public function storeTiposProduto(Request $request){

       // cadastrando tipo de produto
        $validator = $request->validate([
            'tipo'=>'bail|required|string',
        ]);  
        $tipo = new Tipos([
           'tipo' => $request->tipo
        ]); 
                
       $tipo->save();
       return response()->json([
         'res' => 'Cadastro do tipo realizado com sucesso!'
       ], 200);
       
    }

    public function storeProdutoEmpresa(Request $request){
        //user_id do usuario logado no sistema
        $user_id = $request->user()->id;
        //pessoa_id do usuario logado no sistema
        $pessoa_id = User::find($user_id)->pessoa->id;
        //empresa_id da pessoa logada no sistema
        $empresa_id = Pessoas::find($pessoa_id)->empresa->id;

            //crtl + k + crtl + c para comentar varias linhas
            //crtl + k + crtl + u para descomentar varias linhas

        /**
         * para cadastrar um produto antes tenho de saber se ele
         * é simples ou composto, pois um composto é feito de um
         * conjunto de simples, ou seja, um combo
         */
       
   
    }
}

