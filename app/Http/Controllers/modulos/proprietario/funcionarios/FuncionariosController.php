<?php

namespace App\Http\Controllers\modulos\proprietario\funcionarios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\API\BuscarEmpresa;
use App\API\ApiErros;
use App\API\ValidaRequests;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Empresas;


class FuncionariosController extends Controller
{
    public function funcionarioEmpresa(Request $request)
    {
        try{
            $empresaId = BuscarEmpresa::BuscarEmpresa($request);
            $empresa = Empresas::find($empresaId);
            $usersIdsFuncionarios = $empresa->funcionario()->where('funcao_id',3)->select('user_id')->get();
            foreach ($usersIdsFuncionarios as $user){
                $teste[] = DB::table('users')
                    ->where('users.id',$user->user_id)
                    ->join('funcionarios','users.id','=','funcionarios.user_id')
                    ->join('permissoes','users.permissao_id','=','permissoes.id')
                    ->select('users.id','users.nome','users.email','permissoes.nome AS funcao','funcionarios.situacao')->get();
            }
            foreach ($teste as $test){
                $test1 = $test;
            }
            return response()->json($test1,200);
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1055));
            }
            //para opção de produção
            return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir os funcionarios',1055));
        }

    }

    public function updateFuncionario(User $funcionario, Request $request)
    {
        try{
            $retorno = ValidaRequests::validaUpdateFuncionarioEmpresa($request);
            if(!empty($retorno)){
                $arrayErros = $retorno->original;
                return response()->json(['ErrosValida' => $arrayErros],200);
            }
            $usuario = $funcionario->update(['nome' => $request->nome, 'email' => $request->email]);
            $situacao = $funcionario->funcionario()->update(['situacao' =>$request->situacao]);

            if($usuario && $situacao){
                return response()->json('Funcionario atualizado com sucesso',200);
            }else{
                return response()->json('Não atualizado',200);
            }

        }catch (\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1056));
            }
            //para opção de produção
            return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao alterar o funcionario',1056));
        }

    }
    public function desativarFuncionario(User $user)
    {
        try{
            if ( $user->funcionario()->update(['situacao'=>'inativo']) ){
                return response()->json('Funcionário posto como inativo',200);
            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1057));
            }
            //para opção de produção
            return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao desativar o funcionario',1057));
        }

    }
    public function deleteFuncionario(User $user){
        try{
            if( $user->delete() ){
                return response()->json('funcionario excluido com sucesso',200);
            }else{
                return response()->json('funcionario não encontrado',200);
            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1058));
            }
            //para opção de produção
            return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao deletar o funcionario',1058));
        }

    }

}
