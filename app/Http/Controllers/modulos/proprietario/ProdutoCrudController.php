<?php

namespace App\Http\Controllers\modulos\proprietario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use App\Pessoas;
use App\Produtos;
use App\Empresas;

class ProdutoCrudController extends Controller
{
    public function storeProdutoEmpresa(Request $request){
        //user_id do usuario logado no sistema
        $user_id = $request->user()->id;
        //pessoa_id do usuario logado no sistema
        $pessoa_id = User::find($user_id)->pessoa->id;
        //empresa_id da pessoa logada no sistema
        $empresa_id = Pessoas::find($pessoa_id)->empresa->id;

            //crtl + k + crtl + c para comentar varias linhas
            //crtl + k + crtl + u para descomentar varias linhas

        return response()->json([
            $empresa_id
        ]);

        
   
    }
}
