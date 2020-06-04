<?php

namespace App\Http\Controllers\modulos\master\proprietarios;

use App\Empresas;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\API\ApiErros;


//razao_social, cpf, nome, nome de usuario, cidade, rua, bairro
class BuscarProprietarioController extends Controller
{
    public function filtrarPessoaEmpresa(Empresas $empresa){
        try {
            $empresa_id = $empresa->id;
            $query = DB::table('users')
                        ->select('pessoas.id','pessoas.nome','pessoas.telefone',
                                 'users.email AS email','permissoes.nome AS funcao','cidades.nome AS cidade')
                        ->join('pessoas','users.pessoas_id','=','pessoas.id')
                        ->join('permissoes','users.permissoes_id','=','permissoes.id')
                        ->join('cidades','cidades.id','=','pessoas.cidade_id')
                        ->where('pessoas.empresas_id',$empresa_id)
                        ->where('users.permissoes_id','!=',1)
                        ->paginate(10);
            return response()->json($query,200);
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1027));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir os dados',1027));
        }

    }
}
