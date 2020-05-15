<?php

namespace App\Http\Controllers\modulos\master\clientes;

use App\Http\Controllers\Controller;
use App\Pessoas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


//empresa, nome, cidade, bairro, rua
class ListClienteContrller extends Controller
{
    public function listagemClientes(){
        $clientes = DB::table('pessoas')->where('funcoes_id',4)->paginate(10);
        return response()->json($clientes,200);
    }
    public function filtratListaCliente(){
        if(
            is_null(Request()->input('razao_social')) &&
            is_null(Request()->input('nome')) &&
            is_null(Request()->input('cidade')) &&
            is_null(Request()->input('bairro')) &&
            is_null(Request()->input('rua')) &&
            is_null(Request()->input('bairro'))
          ){
             return response()->json(["Nenhum campo de busca preenchido, por favor tente novamente"],200);
        }
        $query = DB::table('pessoas')->join('empresas','pessoas.empresas_id','=','empresas.id')
                                     ->select('pessoas.id','empresas.razao_social','pessoas.nome', 'pessoas.sexo','pessoas.telefone',
                                                'pessoas.cpf','pessoas.cidade','pessoas.rua','pessoas.cep',
                                                'pessoas.bairro'
                                            )
                                     ->where('pessoas.funcoes_id',4)

        //nome da empresa
        ->when(Request()->input('razao_social'), function($query){
            $query->where('empresas.razao_social',Request()->input('razao_social'));
        })

         //nome
        ->when(Request()->input('nome'), function($query){
            $query->where('pessoas.nome',Request()->input('nome'));
        })

        //cidade
        ->when(Request()->input('cidade'), function($query){
            $query->where('pessoas.cidade',Request()->input('cidade'));
        })

        //bairro
        ->when(Request()->input('bairro'), function($query){
            $query->where('pessoas.bairro',Request()->input('bairro'));
        })

        //rua
        ->when(Request()->input('rua'), function($query){
            $query->where('pessoas.rua',Request()->input('rua'));
            $casa = 0;
        })

        ->paginate(10);

        if($query->isEmpty()){
            return response()->json("Nenhum cliente encontrada!",200);
          }
            return response()->json($query,200); 
            
    }
   

}
