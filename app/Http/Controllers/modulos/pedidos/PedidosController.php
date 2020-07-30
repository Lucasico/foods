<?php

namespace App\Http\Controllers\modulos\pedidos;

use App\API\ApiErros;
use App\API\BuscarEmpresa;
use App\API\BuscarIdPedidosEmpresa;
use App\Http\Controllers\Controller;
use App\Item_pedidos;
use App\pedidos;
use App\Produtos;
use App\Situacao_pedidos;
use App\Events\NewPedido;
use Illuminate\Http\Request;
use App\API\ValidaRequests;
use Illuminate\Support\Facades\DB;

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
                $empresaRecebePedido = Produtos::find($produtos[0]);
                $idEmpresa = $empresaRecebePedido->empresa_id;
                event(new NewPedido('novoPedido',$idEmpresa));
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
        $teste = null;
        $pedidos = BuscarIdPedidosEmpresa::buscarPedido($request,1,4);
        foreach ($pedidos as $pedido){
            $teste[] = $this->visualizarPedidoCompleto($pedido->id);
        }
        $pedido->teste = $teste;
        return response()->json($teste,200);
    }
    public function listaPedidosFinalizados(Request $request)
    {
        $teste = null;
        $pedidos = BuscarIdPedidosEmpresa::buscarPedido($request,5,7);
        foreach ($pedidos as $pedido){
            $teste[] = $this->visualizarPedidoCompleto($pedido->id);
        }
        $pedido->teste = $teste;
        return response()->json($teste,200);
    }
    public function filterOpenOrders(Request $request)
    {
        $listOrders = null;
        $order = new PedidosFiltrarController();
        $orders = $order->filtrarPedidos($request,'1','4');
        $typeOrdersData = gettype($orders);
        if($typeOrdersData == 'object') {
            foreach ($orders as $ordere) {
                $listOrders[] = $this->visualizarPedidoCompleto($ordere->id);
            }
            $orders->teste = $listOrders;
            return response()->json($listOrders,200);
        }
        return response()->json($orders,200);
    }
    public function filterFinalizedOrders(Request $request)
    {
        $listOrders = null;
        $order = new PedidosFiltrarController();
        $orders = $order->filtrarPedidos($request,'5','7');
        $typeOrdersData = gettype($orders);
        if($typeOrdersData == 'object') {
            foreach ($orders as $ordere) {
                $listOrders[] = $this->visualizarPedidoCompleto($ordere->id);
            }
            $orders->teste = $listOrders;
            return response()->json($listOrders,200);
        }
        return response()->json($orders,200);
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
    public function visualizarPedidoCompleto($ped)
    {
        try{
            $pedido = pedidos::find($ped);
            $pedido->forma_pagamento;
            $pedido->user;
            $pedido->itens = $pedido->item_pedido()->get();
            foreach ($pedido->itens as $item){
                $pedido->itens->produto_id = $item->produto->select('nome');
            }
            //calcular valor total do pedido ao exibi-lo
            $valor = 0;
            foreach ($pedido->itens as $item){
                $valor = $item->valor + $valor;
            }
            $pedido->valorTotalPedido = $valor;
            return($pedido);
        }catch (\Exception $e){
            if(config('app.debug')){
                return (ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1073));
            }
            //para opção de produção
            return (ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir os pedidos',1073));
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
    public function aceitarPedido(pedidos $pedido){
        DB::beginTransaction();
        try{
            if ( $pedido->update(['situacao_pedido_id' => 2]) ){
                DB::commit();
                return response()->json('O pedido: ' . $pedido->codigo .' foi para a cozinha',200);
            }
        }catch (\Exception $e){
            DB::rollBack();
            if(config('app.debug')){
                return (ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1074));
            }
            //para opção de produção
            return (ApiErros::erroMensageCadastroEmpresa('Houve um erro ao colocar o pedido na cozinha',1074));
        }
    }
    public function cancelarPedido(pedidos $pedido){
        DB::beginTransaction();
        try{
            if ( $pedido->update(['situacao_pedido_id' => 6]) ){
                DB::commit();
                return response()->json('O pedido: ' . $pedido->codigo .' foi cancelado',200);
            }
        }catch (\Exception $e){
            DB::rollBack();
            if(config('app.debug')){
                return (ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1075));
            }
            //para opção de produção
            return (ApiErros::erroMensageCadastroEmpresa('Houve um erro ao cancelar pedido',1075));
        }
    }

}
