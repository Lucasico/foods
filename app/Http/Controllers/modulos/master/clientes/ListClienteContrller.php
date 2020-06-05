<?php

namespace App\Http\Controllers\modulos\master\clientes;

use App\Http\Controllers\Controller;
use App\Pessoas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


//empresa, nome, cidade, bairro, rua
class ListClienteContrller extends Controller
{
    public function listagemClientes ()
    {
        $query = DB ::table ( 'pessoas' )
            -> select ( 'empresas.razao_social' , 'pessoas.nome' , 'pessoas.telefone' ,
                'users.email AS email' , 'cidades.nome AS cidade'
            )
            -> join ( 'empresas' , 'pessoas.empresas_id' , '=' , 'empresas.id' )
            -> join ( 'users' , 'users.pessoas_id' , '=' , 'pessoas.id' )
            -> join ( 'permissoes' , 'users.permissoes_id' , '=' , 'permissoes.id' )
            -> join ( 'cidades' , 'cidades.id' , '=' , 'pessoas.cidade_id' )
            -> join ( 'funcoes' , 'funcoes.id' , '=' , 'pessoas.funcoes_id' )
            -> where ( 'pessoas.funcoes_id' , 4 )
            -> orderBy ( 'pessoas.nome' , 'ASC' )
            -> paginate ( 10 );
        return response () -> json ( $query , 200 );
    }

    public function filtratListaCliente ()
    {
        if (
        is_null ( Request () -> input ( 'buscar' ) )

        ) {
            return response () -> json ( [ "ErrosValida" => "Nenhum campo de busca preenchido, por favor tente novamente" ] , 200 );
        }
        $query = DB ::table ( 'pessoas' )
            -> join ( 'empresas' , 'pessoas.empresas_id' , '=' , 'empresas.id' )
            -> join ( 'cidades' , 'pessoas.cidade_id' , '=' , 'cidades.id' )
            -> join ( 'users' , 'pessoas.id' , '=' , 'users.pessoas_id' )
            -> select ( 'empresas.razao_social' , 'pessoas.nome' , 'pessoas.telefone' ,
                'users.email AS email' , 'cidades.nome AS cidade' )
            -> where ( 'users.permissoes_id' , '=' , 4 )
            //nome da empresa
            -> when ( Request () -> input ( 'buscar' ) , function ( $query ) {
                $query -> where ( 'pessoas.funcoes_id' , '=' , 4 )
                    -> where ( 'empresas.razao_social' , 'like' , '%' . Request () -> input ( 'buscar' ) . '%' )
                    -> orWhere ( 'pessoas.nome' , 'like' , '%' . Request () -> input ( 'buscar' ) . '%' )
                    -> orWhere ( 'cidades.nome' , 'like' , '%' . Request () -> input ( 'buscar' ) . '%' )
                    -> orWhere ( 'users.email' , 'like' , '%' . Request () -> input ( 'buscar' ) . '%' )
                    -> orWhere ( 'pessoas.rua' , 'like' , '%' . Request () -> input ( 'buscar' ) . '%' )
                    -> orWhere ( 'pessoas.bairro' , 'like' , '%' . Request () -> input ( 'buscar' ) . '%' )
                    -> orWhere ( 'pessoas.numero' , 'like' , '%' . Request () -> input ( 'buscar' ) . '%' );
            } )
            -> orderBy ( 'empresas.razao_social' , 'asc' )
            -> paginate ( 10 );
        if ( $query -> isEmpty () ) {
            return response () -> json ( [ "ErrosValida" => "Nenhum cliente encontrada!" ] , 200 );
        }
        return response () -> json ( $query , 200 );
    }
}
