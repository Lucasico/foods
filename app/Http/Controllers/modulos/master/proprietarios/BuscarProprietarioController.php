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
        $query = DB::table('pessoas')
        //nome da empresa
        ->when(Request()->input('nome_empresa'), function($query){

            $query->select('pessoas_id','users.name', 'empresas.razao_social')
                  ->from('pessoas')->join('empresas','pessoas.empresas_id','=','empresas.id')
                  ->join('users','pessoas.id','=','users.pessoas_id')
                  ->where('empresas.razao_social',Request()->input('nome_empresa'));          
          
                  
                  
                 
         })

         //cpf
         ->when(Request()->input('cpf'), function($query){
            $query->where('cpf',Request()->input('cpf'))
                  ->select('pessoas.nome','pessoas.sexo','pessoas.telefone','pessoas.cpf','pessoas.cidade','pessoas.rua','pessoas.cep','pessoas.bairro');
         })

          ->paginate(10);

          if($query->isEmpty()){
            return response()->json("Nenhuma Empresa encontrada!",200);
          }
        
          return response()->json($query,200); 
    }
   
}
