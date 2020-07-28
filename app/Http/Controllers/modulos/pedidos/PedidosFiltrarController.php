<?php

namespace App\Http\Controllers\modulos\pedidos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\API\BuscarEmpresa;
use App\API\ApiErros;
use Illuminate\Support\Facades\DB;

class PedidosFiltrarController extends Controller
{
    public function filtrarPedidos(Request $request, $situacaoInicial, $situacaoFinal)
    {
        try{
            //aberto e situacao
            if (is_null(Request()->input('buscar')) && is_null(Request()->input('situacao'))) {
                return ("Nenhum campo de busca preenchido, por favor tente novamente");
            }
            //busca apenas se buscar existir
            if (!is_null(Request()->input('buscar')) && is_null(Request()->input('situacao'))) {
                $empresaId = BuscarEmpresa::BuscarEmpresa($request);
                $query = DB::table('produtos')
                    ->where('empresa_id','=',$empresaId)
                    ->join('item_pedidos','produtos.id','=','item_pedidos.produto_id')
                    ->join('pedidos','item_pedidos.pedido_id','=','pedidos.id')
                    ->join('users','pedidos.user_id','=','users.id')
                    ->join('formas_pagamentos','pedidos.forma_pagamento_id','=','formas_pagamentos.id')
                    ->join('situacao_pedidos','pedidos.situacao_pedido_id','=','situacao_pedidos.id')
                    ->where('pedidos.situacao_pedido_id','>=',$situacaoInicial)
                    ->where('pedidos.situacao_pedido_id','<=',$situacaoFinal)
                    ->select ('pedidos.*')
                      //  'pedidos.codigo',
                      //  'users.nome AS cliente',
                     //   DB::raw("CONCAT(users.bairro,', ',users.rua,', ',users.numero) AS endereco"),
                     //   'formas_pagamentos.nome AS formaPagamento',
                      //  'pedidos.created_at',
                      //  'pedidos.updated_at',
                      //  'pedidos.observacoes',
                      //  'situacao_pedidos.nome as situacao')
                    ->when(Request()->input('buscar'), function ($query) {
                        $query->where('formas_pagamentos.nome', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('pedidos.codigo', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('pedidos.observacoes', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('users.nome', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('pedidos.created_at', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('users.rua', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('users.numero', 'like', '%' . Request()->input('buscar') . '%');
                    })
                    ->distinct()->orderBy('pedidos.created_at', 'ASC')->paginate(10);
                if ($query->isEmpty()) {
                    return ("Nenhum pedido encontrado!");
                }
                return ($query);
                //busca se os dois existirem
            }else if(!is_null(Request()->input('buscar')) && !is_null(Request()->input('situacao'))){
                $empresaId = BuscarEmpresa::BuscarEmpresa($request);
                $query = DB::table('produtos')
                    ->where('empresa_id','=',$empresaId)
                    ->join('item_pedidos','produtos.id','=','item_pedidos.produto_id')
                    ->join('pedidos','item_pedidos.pedido_id','=','pedidos.id')
                    ->join('users','pedidos.user_id','=','users.id')
                    ->join('formas_pagamentos','pedidos.forma_pagamento_id','=','formas_pagamentos.id')
                    ->join('situacao_pedidos','pedidos.situacao_pedido_id','=','situacao_pedidos.id')
                    ->where('pedidos.situacao_pedido_id','>=',$situacaoInicial)
                    ->where('pedidos.situacao_pedido_id','<=',$situacaoFinal)
                    ->select ('pedidos.*')
                       // 'pedidos.codigo',
                      //  'users.nome AS cliente',
                   //     DB::raw("CONCAT(users.bairro,', ',users.rua,', ',users.numero) AS endereco"),
                     //   'formas_pagamentos.nome AS formaPagamento',
                     //   'pedidos.created_at',
                     //   'pedidos.updated_at',
                     //   'pedidos.observacoes',
                     //   'situacao_pedidos.nome as situacao')
                    ->where('pedidos.situacao_pedido_id',Request()->input('situacao'))
                    ->where(function ($query){
                        $query->where('formas_pagamentos.nome', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('pedidos.codigo', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('pedidos.observacoes', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('users.nome', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('pedidos.created_at', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('users.rua', 'like', '%' . Request()->input('buscar') . '%')
                            ->orWhere('users.numero', 'like', '%' . Request()->input('buscar') . '%');
                    })
                    ->distinct()->orderBy('pedidos.created_at', 'ASC')->paginate(10);
                if ($query->isEmpty()) {
                    return( "Nenhum pedido encontrado!");
                }
                return($query);
            }else if(is_null(Request()->input('buscar')) && !is_null(Request()->input('situacao'))){
                $empresaId = BuscarEmpresa::BuscarEmpresa($request);
                $query = DB::table('produtos')
                    ->where('empresa_id','=',$empresaId)
                    ->join('item_pedidos','produtos.id','=','item_pedidos.produto_id')
                    ->join('pedidos','item_pedidos.pedido_id','=','pedidos.id')
                    ->join('users','pedidos.user_id','=','users.id')
                    ->join('formas_pagamentos','pedidos.forma_pagamento_id','=','formas_pagamentos.id')
                    ->join('situacao_pedidos','pedidos.situacao_pedido_id','=','situacao_pedidos.id')
                    ->where('pedidos.situacao_pedido_id','>=',$situacaoInicial)
                    ->where('pedidos.situacao_pedido_id','<=',$situacaoFinal)
                    ->select ('pedidos.*')
                     //   'pedidos.codigo',
                     //   'users.nome AS cliente',
                     //   DB::raw("CONCAT(users.bairro,', ',users.rua,', ',users.numero) AS endereco")
                     //   'formas_pagamentos.nome AS formaPagamento',
                    //    'pedidos.created_at',
                    //    'pedidos.updated_at',
                    //    'pedidos.observacoes',
                    //    'situacao_pedidos.nome as situacao')
                    ->when(Request()->input('situacao'),function ($query){
                        $query->where('pedidos.situacao_pedido_id',Request()->input('situacao'));
                    })
                    ->distinct()->orderBy('pedidos.created_at', 'ASC')->paginate(10);
                if ($query->isEmpty()) {
                    return("Nenhum pedido encontrado!");
                }
                return($query);
            }
        }catch (Exception $e){
            if(config('app.debug')){
                return (ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1075));
            }
            //para opção de produção
            return (ApiErros::erroMensageCadastroEmpresa('Houve um erro ao exibir os produtos',1075));
        }
    }
}
