<?php

namespace App\Http\Controllers\modulos\master\empresas;

use App\API\ApiErros;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


//razao social, cnpj, cidade, rua, bairro, numero, categoria
class BuscarEmpresaController extends Controller
{
    public function filtraEmpresa(Request $request)
    {
        try {
            if (is_null(Request()->input('buscar')) && is_null(Request()->input('situacao'))) {
                return response()->json(["ErrosValida" => "Nenhum campo de busca preenchido, por favor tente novamente"], 200);
            }
            //busca apenas se buscar existir
            if (!is_null(Request()->input('buscar')) && is_null(Request()->input('situacao'))) {
                $query = DB::table('empresas')->join('cidades','empresas.cidade_id','=','cidades.id')
                    ->select('empresas.razao_social','empresas.cnpj','empresas.situacao','empresas.bairro','empresas.rua',
                        'empresas.cep','empresas.taxaEntrega','empresas.tempoEntrega','empresas.categoria','empresas.telefone',
                        'empresas.celular','empresas.email','empresas.instagram','empresas.numero','cidades.nome')
                    ->when(Request()->input('buscar'), function ($query) {
                        $query->where('empresas.razao_social', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('empresas.cnpj', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('empresas.bairro', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('empresas.rua', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('empresas.categoria', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('empresas.telefone', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('empresas.celular', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('empresas.numero', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('cidades.nome','like','%' . Request()->input('buscar') . '%');
                    })
                    ->orderBy('empresas.razao_social','asc')
                    ->paginate(10);
                if ($query->isEmpty()) {
                    return response()->json(["ErrosValida" => "Nenhuma Empresa encontrada!"], 200);
                }
                return response()->json($query, 200);
                //busca se apenas situação existir
            }else if(is_null(Request()->input('buscar')) && !is_null(Request()->input('situacao'))){
                $query = DB::table('empresas')->join('cidades','empresas.cidade_id','=','cidades.id')
                    ->select('empresas.razao_social','empresas.cnpj','empresas.situacao','empresas.bairro','empresas.rua',
                        'empresas.cep','empresas.taxaEntrega','empresas.tempoEntrega','empresas.categoria','empresas.telefone',
                        'empresas.celular','empresas.email','empresas.instagram','empresas.numero','cidades.nome')
                    ->when(Request()->input('situacao'),function ($query){
                        $query->where('empresas.situacao',Request()->input('situacao'));
                    })
                    ->orderBy('empresas.razao_social','asc')
                    ->paginate(10);
                if ($query->isEmpty()) {
                    return response()->json(["ErrosValida" => "Nenhuma Empresa encontrada!"], 200);
                }
                return Response()->json($query,200);
                //busca se os dois existirem
            }else if(!is_null(Request()->input('buscar')) && !is_null(Request()->input('situacao'))){
                $query = DB::table('empresas')->join('cidades','empresas.cidade_id','=','cidades.id')
                    ->select('empresas.razao_social','empresas.cnpj','empresas.situacao','empresas.bairro','empresas.rua',
                        'empresas.cep','empresas.taxaEntrega','empresas.tempoEntrega','empresas.categoria','empresas.telefone',
                        'empresas.celular','empresas.email','empresas.instagram','empresas.numero','cidades.nome')
                    ->where('empresas.situacao',Request()->input('situacao'))
                    ->where(function ($query){
                        $query->where('empresas.razao_social', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('empresas.cnpj', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('empresas.bairro', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('empresas.rua', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('empresas.categoria', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('empresas.telefone', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('empresas.celular', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('empresas.numero', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('cidades.nome','like','%' . Request()->input('buscar') . '%');
                    })
                    // SELECT empresa.razao_social, empresa...
                    // from empresas
                    // JOIN empresa.cidade_id = cidade_id
                    // where empresa_situacao = situacao and (empresas.razao = request || empresa.cnpj = request)
                    //desconsidenrando o
                    ->paginate(10);
                if ($query->isEmpty()) {
                    return response()->json(["ErrosValida" => "Nenhuma Empresa encontrada!"], 200);
                }
                return Response()->json($query,200);
            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1028));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir os dados da empresa',1028));
        }

    }
}


