<?php


namespace App\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuscarIdPedidosEmpresa
{
    public static function buscarPedido(Request $request, $situacaoInicial, $situacaoFinal)
    {
        try{
            $empresaId = BuscarEmpresa::BuscarEmpresa($request);
            $idPedidosComRepeticoes = DB::table('produtos')
                ->where('empresa_id','=',$empresaId)
                ->join('item_pedidos','produtos.id','=','item_pedidos.produto_id')
                ->join('pedidos','item_pedidos.pedido_id','=','pedidos.id')
                ->join('users','pedidos.user_id','=','users.id')
                ->join('formas_pagamentos','pedidos.forma_pagamento_id','=','formas_pagamentos.id')
                ->join('situacao_pedidos','pedidos.situacao_pedido_id','=','situacao_pedidos.id')
                ->where('pedidos.situacao_pedido_id','>=',$situacaoInicial)
                ->where('pedidos.situacao_pedido_id','<=',$situacaoFinal)
                ->select ('pedidos.id',
                    'pedidos.codigo',
                    'users.nome AS cliente',
                    DB::raw("CONCAT(users.bairro,', ',users.rua,', ',users.numero) AS endereco"),
                    'formas_pagamentos.nome AS formaPagamento',
                    'pedidos.created_at',
                    'pedidos.updated_at',
                    'pedidos.observacoes',
                    'situacao_pedidos.nome as situacao',

            )->distinct()->orderBy('pedidos.created_at', 'ASC')->paginate(10);
            return ($idPedidosComRepeticoes);
        }catch (\Exception $e){
            if(config('app.debug')){
                return (ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1071));
            }
            //para opção de produção
            return (ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir os pedidos',1071));
        }

    }
}
