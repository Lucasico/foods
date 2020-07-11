<?php

namespace App\Http\Controllers\modulos\proprietario\produtos\subCategoria;

use App\Http\Controllers\Controller;
use App\Sub_categorias;
use Illuminate\Http\Request;
use App\API\ApiErros;
use App\API\ValidaRequests;
use App\API\BuscarEmpresa;
use Illuminate\Support\Facades\DB;

class SubCategoriaCrudController extends Controller
{
    public function store(Request $request)
    {
        try {
            $empresa_id = BuscarEmpresa::BuscarEmpresa($request);
            $retorno = ValidaRequests ::validaSubCategoriaCreate( $request );
            if ( ! empty( $retorno ) ) {
                $arrayErros = $retorno -> original;
                return response() -> json( [ 'ErrosValida' => $arrayErros ] , 200 );
            }
            $subCategoria = new Sub_categorias([
                'categoria_id' => $request->categoria_id,
                'nome' => $request->nome,
                'situacao' => 'S',
                'empresa_id' => $empresa_id
            ]);

            if( $subCategoria->save() ){
                return response()->json('Sub-Categoria registrada com sucesso',200);
            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1039));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao Cadastrar a subCategoria',1039));
        }
    }

    public function index(Request $request)
    {
        try {
            $id_empresa = BuscarEmpresa::BuscarEmpresa($request);
            $subCategorias = DB::table('sub_categorias')
                    ->select('sub_categorias.id','sub_categorias.nome','categorias.nome AS categorias','sub_categorias.situacao')
                    ->where('sub_categorias.empresa_id','=',$id_empresa)
                    ->join('categorias','sub_categorias.categoria_id','=','categorias.id')
                    ->orderBy('categorias.nome','ASC')->get();
            return response()->json($subCategorias,200);
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1040));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir as sub-categorias',1040));
        }
    }

    public function show(Sub_categorias $id)
    {
        try {
            $subCategorias = $id->id;
            $subCategorias = DB::table('sub_categorias')
                ->select('sub_categorias.nome','categorias.nome AS categorias','sub_categorias.situacao')
                ->join('categorias','sub_categorias.categoria_id','=','categorias.id')
                ->where('sub_categorias.id','=',$subCategorias)
                ->get();
            return response()->json($subCategorias,200);

        } catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1041));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir a categoria',1041));
        }
    }

    public function update(Sub_categorias $subCategoria, Request $request)
    {
        try{
            $retorno = ValidaRequests::validaSubCategoriaUpdate($request);
            if(!empty($retorno)){
                $arrayErros = $retorno->original;
                return response()->json(['ErrosValida' => $arrayErros],200);
            }
            $subCategoriaData = array_filter($request->all());
            $subCategoria->fill($subCategoriaData);
            if($subCategoria->save()){
                return response()->json('SubCategoria atualizada com sucesso',200);
            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1042));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao atualizar a subCategoria',1042));
        }
    }

    public function desativarCategoria(Sub_categorias $subCategorias){
        try{

            $id = $subCategorias -> id;
            $subCatInativa = Sub_categorias ::find( $id );
            $subCatInativa -> situacao = 'I';

            if( $subCatInativa->save() ){
                return response()->json('Sub-categoria posta como inativa',200);
            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1043));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao Inativar à sub-categoria',1043));
        }
    }


}
