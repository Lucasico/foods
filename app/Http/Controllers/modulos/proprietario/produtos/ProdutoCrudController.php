<?php

namespace App\Http\Controllers\modulos\proprietario\produtos;
use App\API\BuscarEmpresa;
use App\API\ValidaRequests;
use App\Categorias;
use App\combos;
use App\Composicoes;
use App\Empresas;
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
            $auxIngrediente = $request->ingrediente;
            $auxValor = $request->valor;
            $contIngrediente = count($auxIngrediente);
            $contValor = count($auxValor);
            if($contIngrediente === $contValor){
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
                    for ($i = 0; $i < $contIngrediente; $i++){
                        $composicao = Composicoes::find($request->ingrediente[$i])
                            ->produtos()
                            ->attach($product,['valor' => $request->valor[$i]]);
                    }
                }
                return response()->json('Produto Cadastrado com sucesso',200);
            }else{
                return response()->json('Quantidade de ingredintes diverge da quantidade de preços',400);
            }

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
    public function exibirProdutos(Request $request)
    {
        try{
            $empresa_id = BuscarEmpresa::BuscarEmpresa($request);
            $produtos = Empresas::find($empresa_id)
                        ->produto()
                        ->select('id','sub_categoria_id','situacao','preco','tipo','nome','descricao')
                        ->paginate(35);
            foreach ($produtos as $prod){
                $produtos->sub_categoria_id = $prod->sub_categoria->categoria;
            }
            return response()->json($produtos,200);
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1066));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir todos produtos',1066));
        }
    }
    public function desativarProduto(Request $request, Produtos $produto)
    {
        try{
            if($produto->situacao != 'I'){
                $produto->update(['situacao' => 'I']);
                return response()->json('Produto posto como Inativo',200);
            } else {
                return response()->json('Produto já está Inativo',200);
            }
        }catch (Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1067));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao colocar o produto como inativo',1067));
        }

    }
    public function exibirProduto(Request $request, Produtos $produto){
        try{
            if($produto->tipo == 'S' || $produto->tipo == 's'){
                $produto->sub_categoria_id = $produto->sub_categoria()->select('id','nome')->get();
                $produto->ingredientes = $produto->composicao()->select('id','nome_ingredientes')->get();
                return response()->json($produto,200);
            }else{
                $produto->sub_categoria_id = $produto->sub_categoria()->select('id','nome')->get();
                $processaItemCombo = DB::table('combos')
                    ->where('combo_id','=',$produto->id)
                    ->join('produtos','combos.produto_id','=','produtos.id')
                    ->select('produtos.id','produtos.nome','produtos.preco')->get();
                $produto->itemsCombo = $processaItemCombo;
                return response()->json($produto,200);
            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1068));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir o produto',1068));
        }
    }
    public function filtrarProduto(Request $request)
    {
        try {
            $empresa_id = BuscarEmpresa::BuscarEmpresa($request);
            if (is_null(Request()->input('buscar')) && is_null(Request()->input('categoria'))) {
                return response()->json(["ErrosValida" => "Nenhum campo de busca preenchido, por favor tente novamente"], 200);
            }
            //busca apenas se buscar existir
            if (!is_null(Request()->input('buscar')) && is_null(Request()->input('categoria'))) {
                $query = DB::table('produtos')
                    ->join('sub_categorias','produtos.sub_categoria_id','=','sub_categorias.id')
                    ->join('categorias','categorias.id','=','sub_categorias.categoria_id')
                    ->select('produtos.id','produtos.nome','produtos.descricao','produtos.preco',
                            'produtos.tipo','categorias.nome AS categoria')
                    ->where('produtos.empresa_id','=',$empresa_id)
                    ->when(Request()->input('buscar'), function ($query) {
                        $query->where('produtos.nome', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('produtos.descricao', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('produtos.preco', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('produtos.tipo', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('categorias.nome', 'like', '%' . Request()->input('buscar') . '%');

                    })
                    ->orderBy('produtos.nome','asc')
                    ->paginate(10);
                if ($query->isEmpty()) {
                    return response()->json(["ErrosValida" => "Nenhuma Empresa encontrada!"], 200);
                }
                return response()->json($query, 200);
                //busca se apenas categoria existir
            }else if(is_null(Request()->input('buscar')) && !is_null(Request()->input('categoria'))){
                $query = DB::table('produtos')
                    ->join('sub_categorias','produtos.sub_categoria_id','=','sub_categorias.id')
                    ->join('categorias','categorias.id','=','sub_categorias.categoria_id')
                    ->select('produtos.id','produtos.nome','produtos.descricao','produtos.preco',
                        'produtos.tipo','categorias.nome AS categoria')
                    ->where('produtos.empresa_id','=',$empresa_id)
                    ->where('categorias.id',Request()->input('categoria'))
                    ->orderBy('produtos.nome','asc')
                    ->paginate(10);
                if ($query->isEmpty()) {
                    return response()->json(["ErrosValida" => "Nenhuma Empresa encontrada!"], 200);
                }
                return Response()->json($query,200);
                //busca se os dois existirem
            }else if(!is_null(Request()->input('buscar')) && !is_null(Request()->input('categoria'))){
                $query = DB::table('produtos')
                    ->join('sub_categorias','produtos.sub_categoria_id','=','sub_categorias.id')
                    ->join('categorias','categorias.id','=','sub_categorias.categoria_id')
                    ->select('produtos.id','produtos.nome','produtos.descricao','produtos.preco',
                        'produtos.tipo','categorias.nome AS categoria')
                    ->where('produtos.empresa_id','=',$empresa_id)
                    ->where('categorias.id',Request()->input('categoria'))
                    ->where(function ($query){
                        $query->where('produtos.nome', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('produtos.descricao', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('produtos.preco', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('produtos.tipo', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('categorias.nome', 'like', '%' . Request()->input('buscar') . '%');
                    })
                    // SELECT empresa.razao_social, empresa...
                    // from empresas
                    // JOIN empresa.cidade_id = cidade_id
                    // where empresa_situacao = situacao and (empresas.razao = request || empresa.cnpj = request)
                    //desconsidenrando o
                    ->paginate(10);
                if ($query->isEmpty()) {
                    return response()->json(["ErrosValida" => "Nenhuma produto encontrado!"], 200);
                }
                return Response()->json($query,200);
            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1069));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir os dados do produto',1069));
        }

    }
    public function editarProduto(Request $request, Produtos $produto)
    {
        try{
            if($produto->tipo == 'S' || $produto->tipo == 's'){
                $retorno = ValidaRequests::validaUpdateDeProduto($request);
                if(!empty($retorno)){
                    $arrayErros = $retorno->original;
                    return response()->json(['ErrosValida' => $arrayErros],200);
                }
                //começando por produto simples
                $auxIngrediente = $request->ingrediente;
                $auxValor = $request->valor;
                $contIngrediente = count($auxIngrediente);
                $contValor = count($auxValor);
                if($contIngrediente === $contValor){
                    $empresaId = BuscarEmpresa::BuscarEmpresa($request);
                    $updateProduto = $produto->update([
                        'empresa_id' => $empresaId,
                        'situacao' => $request->situacao,
                        'sub_categoria_id' => $request->sub_categoria,
                        'nome' => $request->nome,
                        'descricao' => $request->descricao,
                        'preco' => $request->preco,
                        'tipo' => $request->tipo
                    ]);
                    if( $produto->update() && !is_null($request->ingrediente)){
                        $product = Produtos::find($produto->id);
                        for ($i = 0; $i < $contIngrediente; $i++){
                            $composicao = Composicoes::find($request->ingrediente[$i])
                                ->produtos()
                                ->updateExistingPivot($product,['valor' => $request->valor[$i]]);
                        }
                    }
                    return response()->json('Produto atualizado com sucesso',200);
                }else{
                    return response()->json('Quantidade de ingredintes diverge da quantidade de preços',400);
                }
            }else{
                //esta com erro
                $retorno = ValidaRequests::validaUpdateDeProdutoCombo($request);
                if(!empty($retorno)){
                    $arrayErros = $retorno->original;
                    return response()->json(['ErrosValida' => $arrayErros],200);
                }
                $empresaId = BuscarEmpresa::BuscarEmpresa($request);
                $updateProduto = $produto->update([
                    'empresa_id' => $empresaId,
                    'situacao' => $request->situacao,
                    'sub_categoria_id' => $request->sub_categoria,
                    'nome' => $request->nome,
                    'descricao' => $request->descricao,
                    'preco' => $request->preco,
                    'tipo' => $request->tipo,
                ]);
                if( $updateProduto && !is_null($request->produtos)){
                    $contProdutos = count($request->produtos);
                    $deletaAntigoCombo = DB::table('combos')->where('combo_id','=',$produto->id)->delete();
                    if( $produto->update() && !is_null($request->produtos)){
                        foreach ($request->produtos as $itemCombos){
                            $combo = new combos([
                                'combo_id'=>$produto->id,
                                'produto_id'=>$itemCombos
                            ]);
                            $combo->save();
                        }
                    }
                    return response()->json('Combo Cadastrado com sucesso',200);
                }

            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1067));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao alterar o produto',1067));
        }
    }

}

