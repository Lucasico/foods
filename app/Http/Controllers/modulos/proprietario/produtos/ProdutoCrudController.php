<?php

namespace App\Http\Controllers\modulos\proprietario\produtos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Pessoas;
use App\Produtos;
use App\Tipos;
use App\API\ApiErros;
use Illuminate\Support\Facades\DB;

class ProdutoCrudController extends Controller
{
    public function validaEntradaProdutos(Request $request){
        $request->validate([
            'tipos_id' => 'int',
            'empresas_id' => 'string',
            'nome' => 'required | string',
            'unidade_compra' => 'required | string',
            'descricao' => 'required | string',
            'precoVenda' => 'required | numeric',
            'precoCompra' => 'required | numeric',
            'quantEstoque' => 'required | int',
            'quantMinina' => 'required | int',
        ]);
    }

    //metodo especial para me retornar o tipo de empresa
    public function buscarEmpresa(Request $request){
        try{
            //user_id do usuario logado no sistema
            $user_id = $request->user()->id;
            //pessoa_id do usuario logado no sistema
            $pessoa_id = User::find($user_id)->pessoa->id;
            //empresa_id da pessoa logada no sistema
            $empresa_id = Pessoas::find($pessoa_id)->empresa->id;
            //buscando tipo valido no BD
            return $empresa_id;
        }catch(\Exception $e){
             //para opção de debug
             if(config('app.debug')){
                return response()
                ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1026));
            }
                //para opção de produção
                return response()->
                json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao tentar realizar a busca da empresa por favor tente novamente!',1026));
        }
            
    }

    //listando produtos por empresa
    public function index(Request $request){
        try{
            $empresa_id = self::buscarEmpresa($request);
            $produtos = Produtos::where('empresas_id',$empresa_id);
            return response()->json($produtos->paginate(10),200);
        }catch(\Exception $e){
            //para opção de debug
            if(config('app.debug')){
                return response()
                ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1027));
            }
                //para opção de produção
                return response()->
                json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao tentar lista os produtos empresa por favor tente novamente!',1027));
        }
           
    }

    //cadastrando tipo de produto por empresa
    public function storeTiposProduto(Request $request){
       // cadastrando tipo de produto
       //SIMPLES ou COMPOSTO
        $validator = $request->validate([
            'tipo'=>'bail|required|string',
        ]);  
        $tipo = new Tipos([
           'tipo' => $request->tipo
        ]); 
                
       $tipo->save();
       return response()->json([
         'res' => 'Cadastro do tipo realizado com sucesso!'
       ], 200);
       
    }

    //cadastrando produto por empresa
    public function storeProdutoEmpresa(Request $request){
        $requisicao = $request;  
        $empresa_id = self::buscarEmpresa($requisicao);
        //buscando tipo valido no BD
        $tipoProduto = DB::table('tipos')->where('tipo',$request->tipo)->value('id');
        if(!$tipoProduto){
            return response()->json(['Tipo_invalido' => "Tipo de produto invalido"],401);
        }
        //validando
        self::validaEntradaProdutos($request);
        //criando
        try{
            $produto = new Produtos([
                //lembrando para se cadastrar é necessario o request->tipo
                'tipos_id'=> $tipoProduto,
                'empresas_id' => $empresa_id,
                'nome' => $request->nome,
                'unidade_compra' => $request->unidade_compra,
                'descricao' => $request->descricao,
                'precoVenda' => $request->precoVenda,
                'precoCompra' => $request->precoCompra,
                'quantEstoque' => $request->quantEstoque,
                'quantMinina' => $request->quantMinina
            ]);
            //salvando
            $produto->save();
            return response()->json([
                'produto' => $request->nome . ' cadastrado com sucesso'
            ],201);
        }catch(\Exception $e){
             //para opção de debug
             if(config('app.debug')){
                return response()
                ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1025));
            }
                //para opção de produção
                return response()->
                json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao realizar o cadastro do produto, por favor tente novamente',1025));
        }
            
            //crtl + k + crtl + c para comentar varias linhas
            //crtl + k + crtl + u para descomentar varias linhas

        /**
         * para cadastrar um produto antes tenho de saber se ele
         * é simples ou composto, pois um composto é feito de um
         * conjunto de simples, ou seja, um combo
         */
    }

    //atualizar um produto
    public function updateProduto(Request $request, $id){
        try{
            //valida entrada de dados
            self::validaEntradaProdutos($request);
            $empresa_id = self::buscarEmpresa($request);

            $produto = Produtos::where('empresas_id',$empresa_id)
            ->where('id',$id)
                ->first()
                    ->update($request->all());
            //o first(): retorna o primeiro elemento da coleção que passa em um
            //determinado teste de verdade 
            if($produto){
                return response()->json([
                    'res' => 'Produto alterado com sucesso!',
                ],200);
            }
        }catch(\Exception $e){
             //para opção de debug
            if(config('app.debug')){
                return response()
                ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1028));
            }
                //para opção de produção
                return response()->
                json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao realizar o cadastro do produto, por favor tente novamente',1028));
        }
    }

    //exibindo um produto
    public function showProduto(Request $request,$id){
        try{
            $empresa_id = self::buscarEmpresa($request);
            $produto = Produtos::where('empresas_id',$empresa_id)
                                ->where('id',$id)->get();
            if(isset($produto) || isset($produto->$id)){
                return response()->json([
                    'vazio' => 'Produto não encontrado!',
                ],200);
            }
            return response()->json([
                'res' => $produto
            ],200);
        }catch(\Exception $e){
            if(config('app.debug')){
                return response()
                ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1029));
            }
                //para opção de produção
                return response()->
                json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao tentar exibir o produto, por favor tente novamente!',1029));
        }
    }

    //excluindo um produtp
    public function deleteProduto($id){
        try{
            $produtoDelete = Produtos::find($id);
            $produtoDelete->delete();
            return response()->json([
                'res' => 'produto: ' . $produtoDelete->nome . ' excluido com sucesso',
            ],200);
        }catch(\Exception $e){
            if(config('app.debug')){
                return response()
                ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1030));
            }
                //para opção de produção
                return response()->
                json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao tentar excluir o produto, por favor tente novamente!',1030));
        }
           
    }
}

