<?php

namespace App\Http\Controllers\modulos\proprietario\produtos\ingredientes;

use App\API\ApiErros;
use App\API\ValidaRequests;
use App\Composicoes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ingredientesCrudController extends Controller
{
    public function store(Request $request)
    {
        try {
            $retorno = ValidaRequests ::validaIgredienteCreate( $request );
            if ( ! empty( $retorno ) ) {
                $arrayErros = $retorno -> original;
                return response() -> json( [ 'ErrosValida' => $arrayErros ] , 200 );
            }
            $novoIngrediente = new Composicoes([
                'nome_ingredientes' => $request->nome_ingredientes,
            ]);

            if( $novoIngrediente->save() ){
                return response()->json('Ingrediente registrada com sucesso',200);
            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1045));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao Cadastrar o novo ingrediente',1045));
        }
    }

    public function index()
    {
        try {
            $ingredientes = DB::table('composicoes')->orderBy('nome_ingredientes','ASC')->paginate(10);
            return response()->json($ingredientes,200);
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1046));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir os ingredientes',1046));
        }
    }

    public function show(Composicoes $ingrediente)
    {
        try {
            if (!is_null($ingrediente)) {
                return response($ingrediente, 200);
            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1047));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir o ingrediente',1047));
        }
    }

    public function update(Composicoes $ingrediente, Request $request)
    {
        try{
            $retorno = ValidaRequests::validaIgredienteUpdate($request);
            if(!empty($retorno)){
                $arrayErros = $retorno->original;
                return response()->json(['ErrosValida' => $arrayErros],200);
            }
            $ingredienteData = array_filter($request->all());
            $ingrediente->fill($ingredienteData);
            if($ingrediente->save()){
                return response()->json('Ingrediente atualizada com sucesso',200);
            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1048));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao atualizar o ingrediente',1048));
        }

    }

    public function delete(Composicoes $ingrediente)
    {
        try{
           if( $ingrediente->delete() ){
               return response()->json('Ingrediente excluido com sucesso',200);
           }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1050));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao apagar o ingrediente',1050));
        }
    }
}
