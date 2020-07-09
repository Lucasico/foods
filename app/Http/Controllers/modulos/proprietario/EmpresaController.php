<?php

namespace App\Http\Controllers\modulos\proprietario;

use App\Empresas;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use App\API\BuscarEmpresa;
use App\API\ApiErros;
use App\API\ValidaRequests;
use Illuminate\Support\Facades\DB;


class EmpresaController extends Controller
{
    public function exibirEmpresa(Request $request)
    {
        try{
            $empresaId = BuscarEmpresa::BuscarEmpresa($request);
            $empresa = Empresas::find($empresaId);
            return response()->json($empresa,200);
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1051));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir empresa',1051));
        }

    }

    public function update(Request $request)
    {
        try{
            $empresaId = BuscarEmpresa::BuscarEmpresa($request);
            $retorno = ValidaRequests::validaUpdateEmpresaProprietario($request);
            if(!empty($retorno)){
                $arrayErros = $retorno->original;
                return response()->json(['ErrosValida' => $arrayErros],200);
            }
            $empresa = DB::table('empresas')
                ->where('id',$empresaId)
                ->update([
                    'razao_social' => $request->razao_social,
                    'bairro' => $request->bairro,
                    'rua' => $request->rua,
                    'cep' => $request->cep,
                    'taxaEntrega'=> $request->taxaEntrega,
                    'tempoEntrega'=> $request->tempoEntrega,
                    'celular'=> $request->celular,
                    'telefone'=> $request->telefone,
                    'email'=> $request->email,
                    'instagram'=> $request->instagram,
                    'numero'=> $request->numero,
                ]);
            if( $empresa ){
                return response()->json('Empresa: ' . $request->razao_social . ' atualizada com sucesso', 200);
            }else{
                return response()->json('Nenhum dado de cadastro foi alterado', 200);
            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1052));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao atualizar empresa',1052));
        }
    }

    public function cadastrarFuncionario(Request $request)
    {
        try{
            $empresaId = BuscarEmpresa::BuscarEmpresa($request);
            $retorno = ValidaRequests::validaCadastroDeFuncionario($request);
            if(!empty($retorno)){
                $arrayErros = $retorno->original;
                return response()->json(['ErrosValida' => $arrayErros],200);
            }
            $user = new User([
                //permissao de funcionario
                'permissao_id' => 3,
                'email' =>$request->input('email'),
                'password' =>bcrypt($request->input('password')),
                'password_confirmation' =>$request->input('password_confirmation'),
                'nome'=>$request->input('nome'),
            ]);
            if($user->save()){
                $funcionario = $user->funcionario()->create([
                    'empresa_id' => $empresaId,
                    'funcao_id' => $request->input('funcao_id'),
                    'user_id' => $user->id,
                ]);
            }
            return response()->json([
                'data' => 'Cadastrado de funcionario realizado com sucesso!'
            ], 201);

        }catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1053));
            }
            //para opção de produção
            return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao atualizar',1053));
        }
    }

    public function habilitarDesabilitarFuncionamento(Request $request)
    {
        try{
            $empresaId = BuscarEmpresa::BuscarEmpresa($request);
            if($request->funcionamento == 'S' || $request->funcionamento == 's'){
                $empresa = DB::table('empresas')->where('id',$empresaId)->update(['funcionamento' => 'S']);
                return response()->json('Empresa em horário de funcionamento!',200);
            }elseif ($request->funcionamento == 'N' || $request->funcionamento == 'n'){
                $empresa = DB::table('empresas')->where('id',$empresaId)->update(['funcionamento' => 'N']);
                return response()->json('Empresa fora do horário funcionamento!',200);
            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1054));
            }
            //para opção de produção
            return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro modificar o funcionamento da empresa',1054));
        }

    }
}
