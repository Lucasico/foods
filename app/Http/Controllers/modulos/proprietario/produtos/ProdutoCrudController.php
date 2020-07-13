<?php

namespace App\Http\Controllers\modulos\proprietario\produtos;
use App\API\BuscarEmpresa;
use App\API\ValidaRequests;
use App\Categorias;
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
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1062));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao filtrar os produtos',1062));
        }
    }
    public function subCategorias ( Categorias $categoria, Request $request )
    {
        try{
            $id_categoria = $categoria->id;
            $empresa_id = BuscarEmpresa::BuscarEmpresa($request);
            $sub_categorias = DB::table('sub_categorias')
                ->select(  'id','nome')
                ->where('sub_categorias.categoria_id','=' , $id_categoria)
                ->where('sub_categorias.empresa_id','=',$empresa_id)
                ->orderBy('sub_categorias.nome', 'ASC')
                ->get();
            return response () -> json ($sub_categorias  , 200 );
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1063));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao filtrar os produtos',1063));
        }

    }
    public function listarIngredientes()
    {
        try{
            $ingredientes = Composicoes::all();
            return response()->json($ingredientes,200);
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1064));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir os ingredientes',1064));
        }

    }
    public function listagemDeProdutosSemSelect(Request $request)
    {
        try{
            $empresa_id = BuscarEmpresa::BuscarEmpresa($request);
            $produtos = DB::table('produtos')
                ->select('id','nome')
                ->where('empresa_id','=',$empresa_id)
                ->where('situacao','=','A')
                ->where('tipo','=','S')
                ->orderBy('nome','ASC')
                ->get();
            return response()->json($produtos,200);
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1065));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir os produtos',1065));
        }

    }
}

