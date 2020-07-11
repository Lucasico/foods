<?php

namespace App\API;

use App\API\ApiErros;
use Illuminate\Http\Request;

class BuscarEmpresa
{
    public static function BuscarEmpresa(Request $request)
    {
        try{
            $user = $request->user();
            $funcionario = $user->funcionario()->first();
            $empresa_id = $funcionario->empresa_id;
            return $empresa_id;
        }catch ( \Exception $e ){
            if( config('app.debug') ){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1100));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao buscar a id da empresa',1100));
        }

    }
}
