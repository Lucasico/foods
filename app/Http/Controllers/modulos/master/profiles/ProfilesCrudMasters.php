<?php

namespace App\Http\Controllers\modulos\master\profiles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation;
use Illuminate\Support\Facades\DB;
use App\Pessoas;
use App\User;
use App\API\ApiErros;


class ProfilesCrudMasters extends Controller
{
     //criando Usuario de acesso do tipo master
    public function storeUserMaster(Request $request){
        $emailCadastrado = DB::table('users')->where('email', $request->email)->value('email');
        $erroCadastroEmail = "E-mail ja cadastrado";

        if ($emailCadastrado != null) {
            return response()->json(
               [ "Data" => $erroCadastroEmail ]
            );
        } else {
            try{ 
                //parte de validadação
                $request->validate([
                    'password' => 'required|string|confirmed',
                    'email' => 'required|string|email|unique:users'
                ]);
                //criando o usuario
                $user = new User([
                    'name'=>$request->name,
                    'password' => bcrypt($request->password),
                    'email' => $request->email,
                    'pessoas_id' => $request->pessoas_id,
                    //setando o valor direto para tipo proprietario
                    'permissoes_id' => 1
                ]);
                //salvando
                $user->save();
                return response()->json([
                    'res' => 'Usuario criado com sucesso'
                ], 201);
            }catch(\Exception $e){
                if(config('app.debug')){
                    return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1020));
                }
                    //para opção de produção
                    $retorno = 'houve um erro ao cadastra o usuario master: ' . $request->name;
                    return response()->json(ApiErros::erroMensageCadastroEmpresa($retorno,1020));
            }
        }
    }

    //criando pessoa do tipo master
    public function storePessoaMaster(Request $request){
        $cpfJaCadastrado = DB::table('pessoas')->where('cpf', $request->cpf)->value('cpf');
        if($cpfJaCadastrado != null){
            return response()->json(['Resposta' => "CPF já cadastrado!"],401);
        }else{
            try{  
                //validaçaõ de campos
                $request->validate([
                    'nome' => 'required|string',
                    'cidade'=> 'required|string',
                    'rua'=> 'required|string',
                    'cep'=> 'required|string',
                    'bairro'=> 'required|string',
                    'cpf' => 'required|cpf'
                ]);
                //criando pessoa
                $pessoa = new Pessoas([
                    'empresas_id' => $request->empresa,
                    //setando o valor direto para funcoes de proprietario
                    'funcoes_id' => 1,
                    'nome' => $request->nome,
                    'sexo' => $request->sexo,
                    'telefone' => $request->telefone,
                    'cpf' => $request->cpf,
                    'cidade' => $request->cidade,
                    'rua' => $request->rua,
                    'cep' => $request->cep,
                    'bairro' => $request->bairro
                ]);
                $pessoa->save();
                 return response()->json([
                     'res' => 'Pessoa: ' . $request->nome . ' criada com sucesso!'
                 ], 201);
            }catch(\Exception $e){
                if(config('app.debug')){
                    return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1021));
                }
                    //para opção de produção
                    return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao cadastrar a pessoa',1021));
            } 
        }
    }

}
