<?php

namespace App\Http\Controllers\modulos\master\proprietarios;

use App\Empresas;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Pessoas;
use App\API\ApiErros;
use App\API\ValidaRequests;
use Mockery\Exception;


class ProprietariosCrudController extends Controller
{
    //exibir todos dados do proprietario
    public function exibirDadosProprietario(Pessoas $id){
        try {
            $pessoa_id = $id->id;
            $query = DB::table('users')
                ->select('pessoas.nome','pessoas.telefone','cidades.nome AS cidade','pessoas.bairro',
                    'pessoas.rua','pessoas.numero','funcoes.nome AS funcao','permissoes.nome AS permissao',
                    'users.email AS email','users.situacao')
                ->join('pessoas','users.pessoas_id','=','pessoas.id')
                ->join('permissoes','users.permissoes_id','=','permissoes.id')
                ->join('cidades','cidades.id','=','pessoas.cidade_id')
                ->join('funcoes','funcoes.id','=','pessoas.funcoes_id')
                ->where('pessoas.id',$pessoa_id)
                ->where('users.permissoes_id','!=',1)
                ->first();
            return response()->json($query,200);
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1028));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir os dados',1028));
        }
    }
    //buscar dados do proprietario
    public function buscarUmProprietario(Pessoas $id){
         try {
             $pessoa_id = $id->id;
             $query = DB::table('users')
                 ->select('pessoas.nome','pessoas.telefone',
                     'users.email AS email','users.situacao','pessoas.bairro','pessoas.rua','pessoas.numero')
                 ->join('pessoas','users.pessoas_id','=','pessoas.id')
                 ->join('permissoes','users.permissoes_id','=','permissoes.id')
                 ->join('cidades','cidades.id','=','pessoas.cidade_id')
                 ->join('funcoes','funcoes.id','=','pessoas.funcoes_id')
                 ->where('pessoas.id',$pessoa_id)
                 ->where('users.permissoes_id','!=',1)
                 ->first();
             return response()->json($query,200);
         }catch (\Exception $e){
             if(config('app.debug')){
                 return response()
                     ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1031));
             }
             //para opção de produção
             return response()->
             json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir os dados',1031));
         }
     }
    //criar pessoa proprietaria
    public function storePessoaProprietaria(Empresas $empresas,Request $request){
        try{
            $retorno = ValidaRequests::validaCadastroDePessoa($request);
            if(!empty($retorno)){
                $arrayErros = $retorno->original;
                return response()->json(['ErrosValida' => $arrayErros],200);
            }
            $pessoa = $empresas->pessoas()->create([
                'empresas_id' => $empresas,
                'funcoes_id' => $request->input('funcoes_id'),
                'nome'=>$request->input('nome'),
                'telefone'=>$request->input('telefone'),
                'cidade_id'=>$request->input('cidade_id'),
                'rua'=>$request->input('rua'),
                'bairro'=>$request->input('bairro'),
                'numero'=>$request->input('numero')
            ]);
            $user = $pessoa->users()->create([
                'pessoa_id' => $pessoa,
                'permissoes_id' => $request->input('permissao_id'),
                'email' =>$request->input('email'),
                'password' =>bcrypt($request->input('password')),
                'password_confirmation' =>$request->input('password_confirmation')
            ]);
            return response()->json([
                'data' => 'Cadastrado com sucesso!'
            ], 201);

            }catch(\Exception $e){
                if(config('app.debug')){
                    return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1014));
                }
                //para opção de produção
                return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao atualizar',1014));
            }
    }
    //excluir proprietario
    public function deleteProprietario($id){
        try{
            $pessoa = Pessoas::find($id);
            $excluir = $pessoa->delete();
            if($excluir){
                return response()->json('Funcionario excluido com sucesso', 200);
            }
            dd($excluir);
        }catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1019));
            }
                 //para opção de produção
                return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao apagar',1019));
        }
    }
    //alterar situacao de proprietario
    public function alterSituacaoProprietario(Pessoas $pessoas){
        try {
            $pessoas->users()->update([
                'situacao'=>'Inativo'
            ]);
            return response()->json(['data' => 'Proprietario posto como Inativo!'],200);
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1029));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao alterar a situacao',1029));
        }
    }
    //atualizar proprietario e usuario
    public function alterarProprietarioUsuario(Pessoas $pessoas, Request $request){
        try {
            $retorno = ValidaRequests::validaAtualizaPessoa($request);
            if(!empty($retorno)){
                $arrayErros = $retorno->original;
                return response()->json(['ErrosValida' => $arrayErros],200);
            }
            $pessoa = $pessoas->update([
                'nome' => $request->input('nome'),
                'telefone' => $request->input('telefone'),
                'rua' => $request->input('rua'),
                'bairro' => $request->input('bairro'),
                'numero' => $request->input('numero')
                //'cidade_id' => $request->input('cidade')
            ]);
            $user = $pessoas->users()->update([
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'situacao' => $request->input('situacao')
            ]);
            if($request->input('restaurarSenhaPadrao') === 'sim'){
                $user = $pessoas->users()->update([
                    'password' => bcrypt('familyFoods')
                ]);
            }
            if ($pessoa && $user){
                return response()->json(['data' => 'Atualização realizada com sucesso'],200);
            }
        }catch (Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1030));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao alterar a situacao',1030));
        }

    }
}
