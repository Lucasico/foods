<?php

namespace App\Http\Controllers\modulos\master\empresas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Empresas;
use App\API\ApiErros;
use App\API\ValidaRequests;

class EmpresasCrudController extends Controller
{
    //lista todos
    public function index(){
        return response()->json(Empresas::paginate(10),200);
    }

    //buscar um registro
    public function show(Empresas $id){
        //dessa forma ele já me passa um objeto com este $id
        $data = $id;
       // $id = $data->id;
        return response()->json(['data' => $data],200);
    }

    //inserindo registro
    public function store(Request $request){
        $cnpjCadastrado = DB::table('empresas')->where('cnpj', $request->cnpj)->value('cnpj');
        if($cnpjCadastrado != null){
            return response()->json(['Resposta' => "CNPJ já cadastrado!"],401);
        }else{
            $retorno = ValidaRequests::validaCadastroEmpresa($request);
            if(!empty($retorno)){
                $arrayErros = $retorno->original;
                return response()->json(['ErrosValida' => $arrayErros],422);
            }           
             try{
                $empresa = new Empresas([
                    'razao_social' => $request->razao_social,
                    'cnpj' => $request->cnpj,
                    'situacao' => $request->situacao,
                    'cidade' => $request->cidade,
                    'bairro' => $request->bairro,
                    'rua' => $request->rua,
                    'cep' => $request->cep,
                    'categoria' => $request->categoria,
                    'telefone' => $request->telefone,
                    'celular' => $request->celular,
                    'email' => $request->email,
                    'instagram' => $request->instagram,
                    'taxaEntrega' => $request->taxaEntrega,
                    'tempoEntrega' => $request->tempoEntrega,
                    'numero' => $request->numero

                ]);
                $empresa->save();
                return response()->json([
                    'resposta' => "Empresa cadastrada com sucesso!"
                ],201);
            }catch(\Exception $e){
                //para opção de debug
                if(config('app.debug')){
                    return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1010));
                }
                    //para opção de produção
                    return response()->
                    json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao realizar o cadastro da empresa, por favor tente novamente',1010));
            }
            
        }
        
    }

    //atualizando uma empresa
    public function update(Request $request, $id){
        $testeRetorno = " Empresa Atualizado com sucesso!";
        $retorno = ValidaRequests::validaAtualizaEmpresa($request);
            if(!empty($retorno)){
                $arrayErros = $retorno->original;
                return response()->json(['ErrosValida' => $arrayErros],422);
            }  
            try{  
                Empresas::where('id', $id)->update($request->except('id'));
                return response()->json($testeRetorno,200);
            }catch(\Exception $e){
                //para opção de debug
                if(config('app.debug')){
                    return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1011));
                }
                    //para opção de produção
                    return response()->
                    json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao realizar a atualização neste empresa',1011));
            } 
    }

    //apagando empresa
    public function delete(Empresas $id){
        try{
            $retornoSucesso = " foi removida com sucesso";
            $id->delete();
            return response()->json(['data'=>'Empresa de nome: '
             . $id->razao_social . ' e com CNPJ: ' . $id->cnpj. $retornoSucesso],200);
        }catch(\Exception $e){
            if(config('app.debug')){
                return response()
                ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1012));
            }
                //para opção de produção
                return response()->
                json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao apagar empresa',1012));
        }
    }
}
