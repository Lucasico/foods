<?php

namespace App\Http\Controllers\modulos\master\proprietarios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Empresas;
use App\User;
use App\Pessoas;
use Illuminate\Support\Facades\DB;
//empresa, cpf, nome, nome de usuario, cidade, rua, bairro
class BuscarProprietarioController extends Controller
{
    public function filtrarPessoaEmpresa(){
      if(
        is_null(Request()->input('razao_social')) &&
        is_null(Request()->input('cpf')) &&
        is_null(Request()->input('nome')) &&
        is_null(Request()->input('usuario')) &&
        is_null(Request()->input('cidade')) &&
        is_null(Request()->input('rua')) &&
        is_null(Request()->input('bairro'))
      ){
         return response()->json(["Nenhum campo de busca preenchido, por favor tente novamente"],200);
      }
        $query = DB::table('pessoas')->join('empresas','pessoas.empresas_id','=','empresas.id')
                                     ->join('users','pessoas.id','=','users.pessoas_id')
                                     ->select('pessoas_id','pessoas.nome', 'pessoas.sexo','pessoas.telefone',
                                              'pessoas.cpf','pessoas.cidade','pessoas.rua','pessoas.cep',
                                              'pessoas.bairro','users.name','users.email','empresas.razao_social'
                                             )

        //nome da empresa
        ->when(Request()->input('razao_social'), function($query){
            $query->where('empresas.razao_social',Request()->input('razao_social'));
        })

         //cpf
        ->when(Request()->input('cpf'), function($query){
            $query->where('pessoas.cpf',Request()->input('cpf'));
        })

        //nome pessoal
        ->when(Request()->input('nome'), function($query){
          $query->where('pessoas.nome',Request()->input('nome'));
        })

        //nome de usuaria
        ->when(Request()->input('usuario'), function($query){
          $query->where('users.name',Request()->input('usuario'));
        })

        //cidade
        ->when(Request()->input('cidade'), function($query){
          $query->where('pessoas.cidade',Request()->input('cidade'));
        })

        //rua
        ->when(Request()->input('rua'), function($query){
          $query->where('pessoas.rua',Request()->input('rua'));
        })

        //bairro
        ->when(Request()->input('bairro'), function($query){
          $query->where('pessoas.bairro',Request()->input('bairro'));
        })

        ->paginate(10);

        if($query->isEmpty()){
          return response()->json("Nenhuma pessoa encontrada!",200);
        }
          return response()->json($query,200); 
    }
}
