<?php

namespace App\Http\Controllers\modulos\proprietario\produtos\ingredientes;

use App\API\ApiErros;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuscarIngredienteController extends Controller
{
    public function buscarIngredientes(Request $request)
    {
        try {
            if (is_null(Request()->input('buscar'))) {
                return response()->json(["ErrosValida" => "campo de busca não preenchido, por favor tente novamente"], 200);
            }
            if (!is_null(Request()->input('buscar'))) {
                $query = DB::table('composicoes')
                    ->where(function ($query){
                        $query->Where('id', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('nome_ingredientes', 'like', '%' . Request()->input('buscar') . '%');
                    })
                    ->orderBy('nome_ingredientes','ASC')
                    ->paginate(10);
                if ($query->isEmpty()) {
                    return response()->json(["ErrosValida" => "Nenhuma ingrediente encontrado!"], 200);
                }
                return response()->json($query, 200);
            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1049));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao filtrar o ingrediente',1049));
        }
    }
}
