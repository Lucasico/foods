<?php

namespace App\Http\Controllers\modulos\proprietario\produtos;
use App\API\BuscarEmpresa;
use App\API\ValidaRequests;
use App\Composicoes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Produtos;
use App\API\ApiErros;

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
}

