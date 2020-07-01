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
        $query = DB ::table ( 'users' )
            -> select ( 'permissoes.nome AS funcao' , 'users.nome' , 'users.telefone' ,
                'users.email AS email' , 'cidades.nome AS cidade'
            )
            -> join ( 'permissoes' , 'users.permissao_id' , '=' , 'permissoes.id' )
            -> join ( 'cidades' , 'cidades.id' , '=' , 'users.cidade_id' )
            -> where ( 'users.permissao_id' ,'=', 4 )
            -> orderBy ( 'users.nome' , 'ASC' )
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
        $query = DB ::table ( 'users' )
            //-> join ( 'empresas' , 'pessoas.empresas_id' , '=' , 'empresas.id' )
            -> join ( 'cidades' , 'users.cidade_id' , '=' , 'cidades.id' )
            -> join ( 'permissoes' , 'users.permissao_id' , '=' , 'permissoes.id' )
         //   -> join ( 'users' , 'pessoas.id' , '=' , 'users.pessoas_id' )
            -> select ( 'permissoes.nome AS funcao' , 'users.nome' , 'users.telefone' ,
                'users.email AS email' , 'cidades.nome AS cidade' )
            -> where ( 'users.permissao_id' , '=' , 4 )
            //nome da empresa
            -> when ( Request () -> input ( 'buscar' ) , function ( $query ) {
                $query -> where ( 'users.permissao_id' , '=' , 4 )
                  //  -> where ( 'empresas.razao_social' , 'like' , '%' . Request () -> input ( 'buscar' ) . '%' )
                    -> orWhere ( 'users.nome' , 'like' , '%' . Request () -> input ( 'buscar' ) . '%' )
                    -> orWhere ( 'users.telefone' , 'like' , '%' . Request () -> input ( 'buscar' ) . '%' )
                    -> orWhere ( 'users.email' , 'like' , '%' . Request () -> input ( 'buscar' ) . '%' )
                    -> orWhere ( 'users.rua' , 'like' , '%' . Request () -> input ( 'buscar' ) . '%' )
                    -> orWhere ( 'users.bairro' , 'like' , '%' . Request () -> input ( 'buscar' ) . '%' )
                    -> orWhere ( 'users.numero' , 'like' , '%' . Request () -> input ( 'buscar' ) . '%' );
            } )
            -> orderBy ( 'users.nome' , 'asc' )
            -> paginate ( 10 );
        if ( $query -> isEmpty () ) {
            return response () -> json ( [ "ErrosValida" => "Nenhum cliente encontrada!" ] , 200 );
        }
        return response () -> json ( $query , 200 );
    }
}
