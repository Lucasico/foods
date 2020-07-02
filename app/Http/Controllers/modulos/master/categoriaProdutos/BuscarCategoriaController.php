<?php

namespace App\Http\Controllers\modulos\master\categoriaProdutos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\API\ApiErros;
use Illuminate\Support\Facades\DB;

class BuscarCategoriaController extends Controller
{
    public function buscarCategoria(Request $request)
    {
        try {
            if (is_null(Request()->input('buscar')) && is_null(Request()->input('situacao'))) {
                return response()->json(["ErrosValida" => "Nenhum campo de busca preenchido, por favor tente novamente"], 200);
            }
            if (!is_null(Request()->input('buscar')) && is_null(Request()->input('situacao'))) {
                $query = DB::table('categorias')
                    ->when(Request()->input('buscar'), function ($query) {
                        $query->where('categorias.id', 'like', '%' . Request()->input('buscar') . '%')
                              ->orWhere('categorias.nome', 'like', '%' . Request()->input('buscar') . '%');
                    })
                    ->orderBy('categorias.id','asc')
                    ->paginate(10);
                    if ($query->isEmpty()) {
                        return response()->json(["ErrosValida" => "Nenhuma Empresa encontrada!"], 200);
                    }
                    return response()->json($query, 200);
            }else if(is_null(Request()->input('buscar')) && !is_null(Request()->input('situacao'))){
                $query = DB::table('categorias')
                    ->when(Request()->input('situacao'),function ($query){
                        $query->where('categorias.situacao',Request()->input('situacao'));
                    })
                    ->orderBy('categorias.id','asc')
                    ->paginate(10);
                    if ($query->isEmpty()) {
                        return response()->json(["ErrosValida" => "Nenhuma Empresa encontrada!"], 200);
                    }
                    return Response()->json($query,200);
            }else if(!is_null(Request()->input('buscar')) && !is_null(Request()->input('situacao'))){
                $query = DB::table('categorias')
                    ->where('categorias.situacao',Request()->input('situacao'))
                    ->where(function ($query){
                        $query->where('categorias.id', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('categorias.nome', 'like', '%' . Request()->input('buscar') . '%');
                    })
                    ->paginate(10);
                    if ($query->isEmpty()) {
                        return response()->json(["ErrosValida" => "Nenhuma Empresa encontrada!"], 200);
                    }
                    return Response()->json($query,200);
            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1038));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao filtrar a categoria',1038));
        }
    }
}
