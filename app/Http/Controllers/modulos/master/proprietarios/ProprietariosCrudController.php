<?php

namespace App\Http\Controllers\modulos\master\proprietarios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation;
use Illuminate\Support\Facades\DB;
use App\Pessoas;
use App\User;
use App\API\ApiErros;
use App\API\ValidaRequests;


class ProprietariosCrudController extends Controller
{
    //lista dados do proprietario
    public function show($id){
        try{
            $acao = Pessoas::where('id',$id)->value('id');
           // dd($acao);
            if(!is_null($acao)){
                $pessoa = DB::table('pessoas')->join('empresas','pessoas.empresas_id','=','empresas.id')
                ->join('users','pessoas.id','=','users.pessoas_id')
                ->select('pessoas_id','users.id','pessoas.nome', 'pessoas.sexo','pessoas.telefone',
                         'pessoas.cpf','pessoas.cidade','pessoas.rua','pessoas.cep',
                         'pessoas.bairro','users.name','users.email','empresas.razao_social'
                        )
                //id pessoa
                ->when($id, function($pessoa,$id){
                    $pessoa->where('pessoas.id',$id);
                })
                ->get();
                return response()->json($pessoa,200);
            }else{
                return response()->json("Parametro invalido",200);
            }
        }catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1017));
            }
                 //para opção de produção
                return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir o proprietario',1017));
        }
    }

    //lista de dados proprietarios
    public function listagem(){
        try{
            $pessoasProprietariasEmpresas = DB::table('pessoas')
            ->join('empresas','pessoas.empresas_id','=','empresas.id')
            ->join('users','pessoas.id','=','users.pessoas_id')
            ->select('pessoas_id',"users.id",'pessoas.nome', 'pessoas.sexo','pessoas.telefone',
                     'pessoas.cpf','pessoas.cidade','pessoas.rua','pessoas.cep',
                     'pessoas.bairro','users.name','users.email','empresas.razao_social'
                     )->paginate(10);
            return response()->json($pessoasProprietariasEmpresas,200);
          
        }catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1016));
            }
                 //para opção de produção
                return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao listar os proprietarios',1016));
        }
    }

    //retorna pessoas para cadastro de usuario
    public function retornaPessoaParaCadastroDeUsuario(){
        $pessoas = DB::table('pessoas')->select('id','nome')->where('funcoes_id',2)->get();
        return response()->json(["data" => $pessoas],200);
    }
    //retorna empresas para o cadastro de Pessoa
    public function retornaEmpresasParaCadastroDePessoa(){
        $empresas = DB::table('empresas')->select('id','razao_social')->where('situacao',true)->get();
        return response()->json(["data" => $empresas],200);
    }

    //criando Usuario de acesso do tipo proprietario
    public function storeUserProprietario(Request $request){
        $emailCadastrado = DB::table('users')->where('email', $request->email)->value('email');
        $erroCadastroEmail = "E-mail ja cadastrado";

        if ($emailCadastrado != null) {
            return response()->json(
               [ "Data" => $erroCadastroEmail ]
            );
        } else {
            try{ 
                if ($request->email == "" or $request->password == "") {
                    if ($request->email == "") {
                        return response()->json([
                            'email' => "Campo Email vazio!"
                        ], 401);
                    }
                    if ($request->password == "") {
                        return response()->json([
                            'password' => "Campo senha vazio!"
                        ], 401);
                    } 
                }
                if($request->password != $request->password_confirmation){
                    return response()->json([
                        'Error' => "A confirmação da senha não corresponde."
                    ],401);
                }
                $retorno = ValidaRequests::validaCadastroDotipoProprietario($request);
                if(!empty($retorno)){
                    $arrayErros = $retorno->original;
                    return response()->json(['ErrosValida' => $arrayErros],200);
                }  

                //criando o usuario
                $user = new User([
                    'name'=>$request->name,
                    'password' => bcrypt($request->password),
                    'email' => $request->email,
                    'pessoas_id' => $request->pessoas_id,
                    //setando o valor direto para tipo proprietario
                    'permissoes_id' => 2
                ]);
                //salvando
                $user->save();
                return response()->json([
                    'res' => 'Usuario criado com sucesso'
                ], 201);
            }catch(\Exception $e){
                if(config('app.debug')){
                    return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1013));
                }
                    //para opção de produção
                    $retorno = 'houve um erro ao cadastra o usuario: ' . $request->name;
                    return response()->json(ApiErros::erroMensageCadastroEmpresa($retorno,1013));
            }
        }
    }

    //criar pessoa proprietaria 
    public function storePessoaProprietaria(Request $request){
        $cpfJaCadastrado = DB::table('pessoas')->where('cpf', $request->cpf)->value('cpf');
        if($cpfJaCadastrado != null){
            return response()->json(['Resposta' => "CPF já cadastrado!"],401);
        }else{
            try{  
                $retorno = ValidaRequests::validaCadastroDePessoa($request);
                if(!empty($retorno)){
                    $arrayErros = $retorno->original;
                    return response()->json(['ErrosValida' => $arrayErros],200);
                } 
                //criando pessoa
                $pessoa = new Pessoas([
                    'empresas_id' => $request->empresas_id,
                    //setando o valor direto para funcoes de proprietario
                    'funcoes_id' => 2,
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
                     'res' => 'Cadastrado com sucesso!'
                 ], 201);
            }catch(\Exception $e){
                if(config('app.debug')){
                    return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1014));
                }
                    //para opção de produção
                    return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao cadastrar a pessoa',1014));
            } 
         }
    }

    //atualizar Pessoa cadastrada
    public function updateProprietario(Request $request, $id){
        try{
            $pessoa = Pessoas::find($id);
            if($pessoa->funcoes_id != 2){
                return response()->json([
                    'Data_pessoa'=>'O usuario '. $pessoa->nome . ' não é Proprietario',
                    'Funcao_pessoa'=>$pessoa->funcao->nome
                ]);
            }
            $pessoaData = array_filter($request->all());
            // atualiza esse proprietario
            $pessoa->fill($pessoaData);
            $pessoa->save();
            return response()->json(['Proprietario atualizado com sucesso!'], 200);
        }catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1018));
            }
                 //para opção de produção
                return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao atualizar',1018));

        }
    }

    //atualizar User proprietario
    public function updateUserProprietario(Request $request, $id){
        try{
            $user = User::find($id);
            if($user->permissoes_id != 2){
                return response()->json([
                    'Data_pessoa'=>'O usuario '. $user->name . ' não é Proprietario'
                ]);
            }
            //parte de validadação
            $retorno = ValidaRequests::validaAtualizacaoUserProprietario($request);
            if(!empty($retorno)){
                $arrayErros = $retorno->original;
                return response()->json(['ErrosValida' => $arrayErros],200);
            } 

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->pessoas_id = $request->pessoas_id;
            $user->save();
            return response()->json("Atualizado com sucesso",200);

        }catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1031));
            }
                 //para opção de produção
                return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao atualizar',1031));
        }    
    }

    //excluir proprietario
    public function deleteProprietario($id){
        try{
            $pessoa = Pessoas::find($id);
            if($pessoa->funcoes_id != 2){
                return response()->json([
                    'Data_pessoa'=>'O usuario '. $pessoa->nome . ' não é Proprietario',
                    'Funcao_pessoa'=>$pessoa->funcao->nome
                ]);
            }
            $pessoa->delete();
            return response()->json(['Data'=>'Proprietario excluido com sucesso'], 200);
        }catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1019));
            }
                 //para opção de produção
                return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao apagar',1019));
        }
    }
    
}
