<?php

namespace App\Http\Controllers\modulos\master\categoriaProdutos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Categorias;
use App\API\ValidaRequests;
use App\API\ApiErros;
use Illuminate\Support\Facades\DB;

class CategoriaCrudController extends Controller
{
    public function create(Request $request)
    {
        try {
            $retorno = ValidaRequests::validaCategoriaProduto($request);
            if(!empty($retorno)){
                $arrayErros = $retorno->original;
                return response()->json(['ErrosValida' => $arrayErros],200);
            }
            $categoria = new Categorias([
                'nome' => $request->nome,
            ]);
            if($categoria->save()){
                return response()->json(['data' => 'Categoria registrada com sucesso'],200);
            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1032));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao cadastrar categoria',1032));
        }
    }

    public function index()
    {
        try {
            $categorias = DB::table('categorias')->orderBy('id','ASC')->paginate(10);
            return response()->json($categorias,200);
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1033));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir as categorias',1033));
        }

    }

    public function show(Categorias $id)
    {
        try {
            $categoria = Categorias::find($id);
            if (!is_null($categoria)) {
                return response($categoria, 200);
            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1034));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir a categoria',1034));
        }

    }

    public function delete(Categorias $categoria){
        try {
            if ($categoria->delete()) {
                return response('Categoria excluida com sucesso', 200);
            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1035));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir a categoria',1035));
        }
    }
    //atualizar categoria
    public function update(Categorias $categoria, Request $request)
    {
        try{
            $retorno = ValidaRequests::validaCategoriaProdutoAtualiza($request);
            if(!empty($retorno)){
                $arrayErros = $retorno->original;
                return response()->json(['ErrosValida' => $arrayErros],200);
            }
            $categoriaData = array_filter($request->all());
            $categoria->fill($categoriaData);
            if($categoria->save()){
                return response()->json('Categoria atualizada com sucesso',200);
            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1036));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao atualizar a categoria',1036));
        }

    }
    //desativar Categoria
    public function desativarCategoria(Categorias $categoria){
        try{

            $id = $categoria -> id;
            $catInativa = Categorias ::find( $id );
            $catInativa -> situacao = 'Inativa';
            $catInativa -> save();

            if( $catInativa->save() ){
                return response()->json('Categoria posta como inativa',200);
            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1037));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao Inativar à categoria',1037));
        }
    }

    //filtrar categoria


}
