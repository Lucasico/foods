<?php

namespace App\Http\Controllers\modulos\proprietario\funcionarios;

use App\Funcionarios;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\API\BuscarEmpresa;
use App\API\ApiErros;
use App\API\ValidaRequests;
use App\User;
use Illuminate\Support\Facades\DB;


class FuncionariosController extends Controller
{
    public function funcionarioEmpresa(Request $request)
    {
        try{
            $empresaId = BuscarEmpresa::BuscarEmpresa($request);
            $todosFuncionarios = Funcionarios::where('empresa_id',$empresaId)->where('funcao_id',3)->get();
            foreach ($todosFuncionarios as $func){
                $DadosFuncionarios[] = User::find($func->user_id);
            }
            return response()->json($DadosFuncionarios,200);
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
    public function exibirFuncionario(User $user)
    {
        try{
            $id = $user->id;
            $funcionario = DB::table('users')
                ->where('users.id',$id)
                ->join('funcionarios','users.id','=','funcionarios.user_id')
                ->join('permissoes','users.permissao_id','=','permissoes.id')
                ->select('users.nome','users.email','funcionarios.situacao','permissoes.nome AS funcao')
                ->get();
            return response()->json($funcionario,200);
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1059));
            }
            //para opção de produção
            return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir',1059));
        }

    }

}
