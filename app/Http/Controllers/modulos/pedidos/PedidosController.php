<?php

namespace App\Http\Controllers\modulos\pedidos;

use App\API\ApiErros;
use App\API\BuscarEmpresa;
use App\API\BuscarIdPedidosEmpresa;
use App\Empresas;
use App\Http\Controllers\Controller;
use App\Item_pedidos;
use App\pedidos;
use App\Produtos;
use App\Situacao_pedidos;
use Illuminate\Http\Request;
use App\API\ValidaRequests;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class PedidosController extends Controller
{
    public function criarPedido(Request $request)
    {
        try{
            $controleAddItens = $request->adicionarNovoItem;
            foreach ($request->itens as $item){
                $calcularValorUnitario[] =  $this->calcularValorUnitario(Produtos::find($item['produto']), $item['quantidade']);
                $produtos[] = $item['produto'];
                $quantidadeItens[] = $item['quantidade'];
            }
            $pedido = $this->processarPedido($calcularValorUnitario, $produtos, $quantidadeItens, $controleAddItens, $request);
            if( $pedido === 'pedidoRealizadoComSucesso'){
                return response()->json('Pedido realizado com sucesso',200);
            }
        }catch (\Exception $e){
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1068));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao buscar itens',1068));
        }
    }
    public function calcularValorUnitario(Produtos $produtos, $quantidade)
    {
        $produtos->composicao = $produtos->composicao()->get();
        $ingrediente = $produtos->composicao()->select('id')->first();
            if( !is_null($ingrediente) ){
                try {
                    foreach ($produtos->composicao as $ingredientes){
                        $precoAdicional[] = $ingredientes->pivot->valor;
                    }
                    $precoDeAdicionais = 0;
                    foreach ($precoAdicional as $precoAdd){
                        $precoDeAdicionais = $precoAdd + $precoDeAdicionais;
                    }
                    $precoFinal = $quantidade * ($produtos->preco + $precoDeAdicionais);
                    return $precoFinal;
                }catch (\Exception $e){
                    if(config('app.debug')){
                        return response()
                            ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1069));
                    }
                    //para opção de produção
                    return response()->
                    json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao calcular o preco do item',1069));
                }
            }else{
                $precoFinal = $produtos->preco * $quantidade;
                return $precoFinal;
            }
    }
    public function processarPedido($valorUnitarioDoItem, $produtos, $quantidadeItens, $controleAddItens, Request $request)
    {
        DB::beginTransaction();
        try{
                $retorno = ValidaRequests::validaCreatePedido($request);
                if(!empty($retorno)){
                    $arrayErros = $retorno->original;
                    return ['ErrosValida' => $arrayErros];
                }
                $pedido = new pedidos([
                    'user_id' => $request->user()->id,
                    'situacao_pedido_id' => 1,
                    'forma_pagamento_id' => $request->forma_pagamento,
                    'observacoes' => $request->observacao,
                ]);
                $pedido->save();
                for($i = 0; $i < count($quantidadeItens); $i++){
                    $addItensPedido = $pedido->item_pedido()->saveMany([
                        new Item_pedidos([
                            'produto_id'=>$produtos[$i],
                            'pedido_id'=>$pedido->id,
                            'quantidade'=>$quantidadeItens[$i],
                            'valor' => $valorUnitarioDoItem[$i]
                        ])
                    ]);
                }
                DB::commit();
                return 'pedidoRealizadoComSucesso';
        }catch (\Exception $e){
            DB::rollBack();
            if(config('app.debug')){
                return response()
                    ->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1070));
            }
            //para opção de produção
            return response()->
            json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao realizar pedido',1070));
        }
    }
    public function listaPedidosEmAndamento(Request $request)
    {
        $pedidos = BuscarIdPedidosEmpresa::buscarPedido($request,1,4);
        return response()->json($pedidos,200);
    }
    public function listaPedidosFinalizados(Request $request)
    {
        $pedidos = BuscarIdPedidosEmpresa::buscarPedido($request,5,7);
        return response()->json($pedidos,200);
    }
    public function situacoesPedidos(){
        try{
            $situacoesPedidos = Situacao_pedidos::get();
            return response($situacoesPedidos,200);
        }catch (\Exception $e){
            if(config('app.debug')){
                return (ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1072));
            }
            //para opção de produção
            return (ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir as situacoes dos pedidos',1072));
        }

    }
    public function alterarSituacaoPedido(pedidos $pedido, Request $request){
        try{
            DB::beginTransaction();
            $situacao = Situacao_pedidos::find($request->situacaoPedido);
            $retorno = ValidaRequests::validaAtualizarStatusPedido($request);
            if(!empty($retorno)){
                $arrayErros = $retorno->original;
                return ['ErrosValida' => $arrayErros];
            }
            if( $pedido->situacao_pedido_id != $request->situacaoPedido ){
                $atualizarPedido = $pedido->update(['situacao_pedido_id' => $request->situacaoPedido]);
                if( $atualizarPedido ){
                    DB::commit();
                    return response()->json('O pedido com código: ' . $pedido->codigo . ' foi atualizado para: ' . $situacao->nome,200);
                }
            }
            return response()->json('O pedido com código: ' . $pedido->codigo . ' já se encontra: ' .  $situacao->nome,200);
        }catch (\Exception $e){
            DB::rollBack();
            if(config('app.debug')){
                return (ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1072));
            }
            //para opção de produção
            return (ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir as situacoes dos pedidos',1072));
        }

    }
    public function visualizarPedidoCompleto(pedidos $pedido)
    {
        try{
            //pegando dados 2
            $pedido->itens  = $pedido->item_pedido()->get();
            foreach ($pedido->itens as $item){
                $pedido->itens->produto_id = $item->produto->select('nome');
            }
            //pegando dados 1
            $pedido->user_id = $pedido->user()->get();
            $pedido->situacao_pedido_id = $pedido->situacao_pedido()->get();
            $pedido->forma_pagamento_id = $pedido->forma_pagamento()->get();
            //calcular valor total do pedido ao exibi-lo
            $valor = 0;
            foreach ($pedido->itens as $item){
                $valor = $item->valor + $valor;
            }
            $pedido->valorTotalPedido = $valor;
            return response()->json($pedido,200);
        }catch (\Exception $e){
            if(config('app.debug')){
                return (ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1073));
            }
            //para opção de produção
            return (ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir as situacoes dos pedidos',1073));
        }
    }

    //error neste controller
    public function countPedidosCancelados(Request $request)
    {
        $empresaId = BuscarEmpresa::BuscarEmpresa($request);
        $idPedidosComRepeticoes = DB::table('produtos')
            ->where('empresa_id','=',$empresaId)
            ->join('item_pedidos','produtos.id','=','item_pedidos.produto_id')
            ->join('pedidos','item_pedidos.pedido_id','=','pedidos.id')
            ->join('situacao_pedidos','pedidos.situacao_pedido_id','=','situacao_pedidos.id')
            ->where('pedidos.situacao_pedido_id','=',6)
            ->distinct()->orderBy('pedidos.created_at', 'ASC')->paginate(10);
        return response()->json($idPedidosComRepeticoes,200);
    }
}
