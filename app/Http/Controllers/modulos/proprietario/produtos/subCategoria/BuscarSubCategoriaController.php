<?php

namespace App\Http\Controllers\modulos\proprietario\produtos\subCategoria;

use App\API\ApiErros;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuscarSubCategoriaController extends Controller
{
    public function buscarSubCategoria(Request $request)
    {
        try {
            if (is_null(Request()->input('buscar'))) {
                return response()->json(["ErrosValida" => "campo de busca não preenchido, por favor tente novamente"], 200);
            }
            if (!is_null(Request()->input('buscar'))) {
                $query = DB::table('sub_categorias')
                        ->join('categorias','sub_categorias.categoria_id','=','categorias.id')
                        ->select('sub_categorias.nome','categorias.nome AS categorias','sub_categorias.situacao')
                        ->where(function ($query){
                            $query->Where('categorias.nome', 'like', '%' . Request()->input('buscar') . '%')
                                ->orWhere('sub_categorias.situacao', 'like', '%' . Request()->input('buscar') . '%')
                                ->orWhere('sub_categorias.nome','like','%' . Request()->input('buscar') . '%');
                        })
                    ->orderBy('sub_categorias.nome','ASC')
                    ->paginate(10);
                if ($query->isEmpty()) {
                    return response()->json(["ErrosValida" => "Nenhuma sub-categoria encontrada!"], 200);
                }
                return response()->json($query, 200);
            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1044));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao filtrar a Sub-categoria',1044));
        }
    }
}
