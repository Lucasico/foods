<?php

namespace App\Http\Controllers\modulos\proprietario\produtos;
use App\API\BuscarEmpresa;
use App\API\ValidaRequests;
use App\combos;
use App\Composicoes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Produtos;
use App\API\ApiErros;
use Illuminate\Support\Facades\DB;

class ProdutoCrudController extends Controller
{
    public function create(Request $request)
    {
        try {
            $retorno = ValidaRequests::validaCadastroDeProduto($request);
            if(!empty($retorno)){
                $arrayErros = $retorno->original;
                return response()->json(['ErrosValida' => $arrayErros],200);
            }
            $empresaId = BuscarEmpresa::BuscarEmpresa($request);
            $produto = new Produtos([
                'empresa_id' => $empresaId,
                'situacao' => 'A',
                'sub_categoria_id' => $request->sub_categoria,
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'preco' => $request->preco,
                'tipo' => $request->tipo
            ]);
            if( $produto->save() && !is_null($request->ingrediente)){
                $product = Produtos::find($produto->id);
                $composicao = Composicoes::find($request->ingrediente)
                    ->produtos()
                    ->attach($product,['valor' => $request->valor]);
            }
            return response()->json('Produto Cadastrado com sucesso',200);
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1060));
            }
            //para opção de produção
            return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao cadastrar o produto',1060));
        }
    }
    public function createCombo(Request $request)
    {
        try {
            $retorno = ValidaRequests::validaCadastroDeCombo($request);
            if(!empty($retorno)){
                $arrayErros = $retorno->original;
                return response()->json(['ErrosValida' => $arrayErros],200);
            }
            $empresaId = BuscarEmpresa::BuscarEmpresa($request);
            $produto = new Produtos([
                'empresa_id' => $empresaId,
                'situacao' => 'A',
                'sub_categoria_id' => $request->sub_categoria,
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'preco' => $request->preco,
                'tipo' => $request->tipo
            ]);
            if( $produto->save() && !is_null($request->produtos)){
                foreach ($request->produtos as $itemCombos){
                    $combo = new combos([
                        'combo_id'=>$produto->id,
                        'produto_id'=>$itemCombos
                    ]);
                    $combo->save();
                }
            }
            return response()->json('Combo Cadastrado com sucesso',200);
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1061));
            }
            //para opção de produção
            return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao cadastrar o Combo',1061));
        }
    }
    public function listarProdutosDisponiveisParaCombo(Request $request)
    {
        try {

            if (is_null(Request()->input('buscar'))) {
                return response()->json(["ErrosValida" => "campo de busca não preenchido, por favor tente novamente"], 200);
            }
            if (!is_null(Request()->input('buscar'))) {
                $empresa_id = BuscarEmpresa::BuscarEmpresa($request);
                $query = DB::table('produtos')
                    ->select('produtos.nome','produtos.id')
                    ->where('produtos.empresa_id', '=',$empresa_id)
                    ->where('produtos.situacao','=','A')
                    ->Where('produtos.tipo','=','S')
                    ->where(function ($query){
                        $query->Where('produtos.nome', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('produtos.preco','like','%' . Request()->input('buscar') . '%')
                            ->orWhere('produtos.descricao','like','%' . Request()->input('buscar') . '%');
                    })
                    ->orderBy('produtos.nome','ASC')
                    ->get();
                if ($query->isEmpty()) {
                    return response()->json(["ErrosValida" => "Nenhuma sub-categoria encontrada!"], 200);
                }
                return response()->json($query, 200);
            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1061));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao filtrar os produtos',1061));
        }
    }
}

