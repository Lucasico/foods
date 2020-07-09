<?php

namespace App\Http\Controllers\modulos\master\proprietarios;

use App\Empresas;
use App\Funcionarios;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Pessoas;
use App\API\ApiErros;
use App\API\ValidaRequests;
use Mockery\Exception;


class ProprietariosCrudController extends Controller
{
    //exibir todos dados do proprietario
    public function exibirDadosProprietario(User $id){
        try {
            $user_id = $id->id;
            $query = DB::table('users')
                ->select('users.nome','users.telefone','cidades.nome AS cidade','users.bairro',
                    'users.rua','users.numero','permissoes.nome AS permissao',
                    'users.email AS email','funcionarios.situacao')
                ->join('funcionarios','users.id','=','funcionarios.user_id')
                ->join('permissoes','users.permissao_id','=','permissoes.id')
                ->join('cidades','cidades.id','=','users.cidade_id')
                //->join('funcoes','funcoes.id','=','pessoas.funcoes_id')
                ->where('users.id',$user_id)
                ->where('users.permissao_id','=',2)
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
    public function buscarUmProprietario(User $id){
         try {
             $user_id = $id->id;
             $query = DB::table('users')
                 ->select('users.nome','users.telefone',
                     'users.email AS email','funcionarios.situacao','users.bairro','users.rua','users.numero')
                 ->join('permissoes','users.permissao_id','=','permissoes.id')
                 ->join('cidades','cidades.id','=','users.cidade_id')
                 ->join('funcionarios','users.id','=','funcionarios.user_id')
                 ->where('users.id',$user_id)
                 ->where('users.permissao_id','=',2)
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
            $user = new User([
                'permissao_id' => 3,
                'cidade_id'=>$request->input('cidade_id'),
                'email' =>$request->input('email'),
                'password' =>bcrypt($request->input('password')),
                'password_confirmation' =>$request->input('password_confirmation'),
                'nome'=>$request->input('nome'),
                'telefone'=>$request->input('telefone'),
                'rua'=>$request->input('rua'),
                'bairro'=>$request->input('bairro'),
                'numero'=>$request->input('numero'),
            ]);
            if($user->save()){
                $funcionario = $user->funcionario()->create([
                    'empresa_id' => $empresas->id,
                    'funcao_id' => $request->input('funcoes_id'),
                    'user_id' => $user->id,
                ]);
            }
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
            $funcionario = User::find($id);
            $excluir = $funcionario->delete();
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
    public function alterSituacaoProprietario(User $user){
        try {
            $user->funcionario()->update([
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
    public function alterarProprietarioUsuario(User $pessoas, Request $request){
        try {
            $retorno = ValidaRequests::validaAtualizaPessoa($request);
            if ( !empty($retorno) ) {
                $arrayErros = $retorno->original;
                return response()->json(['ErrosValida' => $arrayErros], 200);
            }
            $pessoa = $pessoas->update([
                'nome' => $request->input('nome'),
                'telefone' => $request->input('telefone'),
                'email' => $request->input('email'),
                'rua' => $request->input('rua'),
                'bairro' => $request->input('bairro'),
                'numero' => $request->input('numero')
                //'cidade_id' => $request->input('cidade')
            ]);
            $user  = $pessoas->funcionario()->update([
                'situacao' => $request->input('situacao')
            ]);
            if ( $request->input('restaurarSenhaPadrao') === 'sim' ) {
                $user = $pessoas->update([
                    'password' => bcrypt('familyFoods')
                ]);
            } else {
                $senha = $pessoas->password;
                $manterPassword = $pessoas->update([
                    'password' => $senha
                ]);
            }
            if ( $pessoa && $user ) {
                return response()->json(['data' => 'Atualização realizada com sucesso'], 200);
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
