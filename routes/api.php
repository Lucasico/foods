<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route ::middleware ( 'auth:api' ) -> get ( '/user' , function ( Request $request ) {
    return $request -> user ();
} );

Route ::prefix ( 'auth' ) -> group ( function () {

    Route ::get ( 'invalido' , 'AutentificadorController@invalido' ) -> name ( "invalido" );
    Route ::post ( 'registro' , 'AutentificadorController@registro' ) -> name('registroAux');
    Route ::get ( 'index' , 'AutentificadorController@index' );
    Route ::post ( 'login' , 'AutentificadorController@login' );


    Route ::middleware ( 'auth:api' ) -> group ( function () {
        Route ::post ( 'logout' , 'AutentificadorController@logout' );
        //Group routes mastes
        Route ::middleware ( 'master' ) -> group ( function () {
            //dashboard
            Route ::prefix ( 'dataDashboard' ) -> group ( function () {
                //quantidade de empresa
                Route ::get ( '/dashboard/quantidadeEmpresas' , 'modulos\master\dashboard\DashboardMasterController@quantidadeEmpresas' )->name('quantEmpresas');
                //quantidade de cliente
                Route ::get ( '/dashboard/quantidadeClientes' , 'modulos\master\dashboard\DashboardMasterController@quantidadeClientes' )->name('quantCliente');
                //quantidade de produtos
                Route ::get ( '/dashboard/quantidadeProdutos' , 'modulos\master\dashboard\DashboardMasterController@quantidadeProdutos' )->name('quantProdutos');
                //listagem de estados
                Route ::get ( '/estados' , 'modulos\master\util\ControllerUfCidade@listEstados' ) -> name ( 'estados' );
                //listagem de cidades
                Route ::get ( '/{estado}/cidades' , 'modulos\master\util\ControllerUfCidade@cidadesDoEstado' ) -> name ( 'cidades' );
            } );

            //routes de empresas
            Route ::prefix ( 'empresas' ) -> group ( function () {
                /**
                 * parte basica de empresa CRUD
                 */
                //atualizando uma empresa
                Route ::put ( '/{id}' , 'modulos\master\empresas\EmpresasCrudController@update' )->name('atualizarEmpresa');

                Route ::get ( '/mostrar/{empresa}' , 'modulos\master\empresas\EmpresasCrudController@extraParaExibirDadosCompletos' )
                    -> name ( 'mostrarDadosDeUmaEmpresa' );

                //atualizar situacao
                Route ::get ( '/{empresas}' , 'modulos\master\empresas\EmpresasCrudController@updateSituacao' )
                    -> name ( 'atualizarSituacao' );

                //excluindo uma empresa
                Route ::delete ( '/{id}' , 'modulos\master\empresas\EmpresasCrudController@delete' )
                    -> name ( 'excluirEmpresa' );

                //buscar um registro
                Route ::get ( '/buscar/{id}' , 'modulos\master\empresas\EmpresasCrudController@show' )
                    -> name ( 'exibirUmaEmpresa' );

                //lista
                Route ::get ( '/' , 'modulos\master\empresas\EmpresasCrudController@index' )
                    -> name ( 'listarTodasEmpresas' );

                //criando empresa
                Route ::post ( '/' , 'modulos\master\empresas\EmpresasCrudController@store' );

                //filtrarEmpresa
                Route ::post ( '/filtrar' , 'modulos\master\empresas\BuscarEmpresaController@filtraEmpresa' )->name('filtrar_empresa');

            } );

            //routes para categoriaProdutos
            Route::prefix('categoriaProdutos') -> group(function (){

                Route ::get('/lista','modulos\master\categoriaProdutos\CategoriaCrudController@index')->name('listaCategorias');
                Route ::delete('/{categoria}','modulos\master\categoriaProdutos\CategoriaCrudController@delete')->name('excluirCategoria');
                Route ::get('/{id}','modulos\master\categoriaProdutos\CategoriaCrudController@show')->name('exibirCategoria');
                Route ::post('/novo','modulos\master\categoriaProdutos\CategoriaCrudController@create')->name('criarCategoria');
                Route ::put('/{categoria}','modulos\master\categoriaProdutos\CategoriaCrudController@update')->name('atualizarCategoria');
                Route ::put('/situacao/{categoria}','modulos\master\categoriaProdutos\CategoriaCrudController@desativarCategoria')->name('desativaSituacao');
                Route ::post('/filtrar','modulos\master\categoriaProdutos\BuscarCategoriaController@buscarCategoria')->name('buscarCategoria');
            });

            //routes de proprietarios
            Route ::prefix ( 'proprietario' ) -> group ( function () {

                //atualizar proprietario e usuario
                Route ::put ( '/atualizar/{pessoas}' , 'modulos\master\proprietarios\ProprietariosCrudController@alterarProprietarioUsuario' )
                    -> name ( 'atualizarPessoaUsuario' );

                //alterar Situação de proprietario
                Route ::put ( '/situacao/{user}' , 'modulos\master\proprietarios\ProprietariosCrudController@alterSituacaoProprietario' )
                    -> name ( 'alterarSituacaoProprietario' );

                //route para excluir um proprietario
                Route ::delete ( '/{id}' , 'modulos\master\proprietarios\ProprietariosCrudController@deleteProprietario' );

                //route para filtrar pessoas
                Route ::get ( '/filtro/{empresa}' ,
                    'modulos\master\proprietarios\BuscarProprietarioController@filtrarPessoaEmpresa' )
                    -> name ( 'listagemDePessoasEmpresa' );

                //buscar proprietario
                Route ::get ( '/buscar/{id}' ,
                    'modulos\master\proprietarios\ProprietariosCrudController@buscarUmProprietario' )
                    -> name ( 'ExibirUmProprietario' );

                //criando PessoaProprietaria
                Route ::post ( '/{empresas}' ,
                    'modulos\master\proprietarios\ProprietariosCrudController@storePessoaProprietaria' ) -> name ( 'pessoa' );

                //exibirDadosProprietario
                Route ::get ( '/exibir/{id}' , 'modulos\master\proprietarios\ProprietariosCrudController@exibirDadosProprietario' )
                    -> name ( 'exibirDadosProprietario' );
                //routes para categorias em proprietario para cadastro de sub-categorias
                Route::prefix('categoriaProdutos') -> group(function (){
                    Route ::get('/lista','modulos\master\categoriaProdutos\CategoriaCrudController@index')->name('listaCategorias');
                    Route ::get('/{id}','modulos\master\categoriaProdutos\CategoriaCrudController@show')->name('exibirCategoria');
                    Route ::post('/filtrar','modulos\master\categoriaProdutos\BuscarCategoriaController@buscarCategoria')->name('buscarCategoria');
                });

            } );

            Route ::prefix ( 'clientes' ) -> group ( function () {
                //route para filtragem de clientes
                Route ::post ( '/filtrar' , 'modulos\master\clientes\ListClienteContrller@filtratListaCliente' ) -> name ( "fitragem de clientes" );
                //route listagem de clientes
                Route ::get ( '/lista' , 'modulos\master\clientes\ListClienteContrller@listagemClientes' ) -> name ( "listaClientes" );
            } );

            //lista referente a usuarios Master
            Route ::prefix ( 'UsersMasters' ) -> group ( function () {
                Route ::post ( '/' , 'modulos\master\profiles\ProfilesCrudMasters@storeUserMaster' );
                Route ::post ( '/cadPessoMaster' , 'modulos\master\profiles\ProfilesCrudMasters@storePessoaMaster' );
            } );

        } );

        //Group routes proprietario
        Route ::middleware ( 'proprietario' ) -> group ( function () {
            //route para cadastro de tipo
            Route ::post ( '/cadTipoProduto' , 'modulos\proprietario\produtos\ProdutoCrudController@storeTiposProduto' );
            //route para cadastro de produto por empresas
            Route ::post ( '/cadProdutoEmpresa' , 'modulos\proprietario\produtos\ProdutoCrudController@storeProdutoEmpresa' );
            //route para lista de produtos por empresas
            Route ::get ( '/listaProdutoEmpresa' , 'modulos\proprietario\produtos\ProdutoCrudController@index' );
            //route para atualizar um produto por empresa
            Route ::put ( '/atualizaProdutoEmpresa/{id}' , 'modulos\proprietario\produtos\ProdutoCrudController@updateProduto' );
            //route para exibir um produto
            Route ::get ( '/produtoEmpresa/{id}' , 'modulos\proprietario\produtos\ProdutoCrudController@showProduto' );
            //route para excluir um produto
            Route ::delete ( '/excluirProduto/{id}' , 'modulos\proprietario\produtos\ProdutoCrudController@deleteProduto' );
            //route para inserir produto no combo
            Route ::post ( '/comboProduto/{id}' , 'modulos\proprietario\produtos\produtoCrudController@createComboProdutos' );

        } );

        //Group routes "funcionario"
        //atençaõ para combinação de permissão + função para definir function
        Route ::middleware ( 'funcionario' ) -> group ( function () {
            // Route::get('itens', 'EmularController@index');
        } );

        //Group route para cliente
        Route ::middleware ( 'cliente' ) -> group ( function () {
            // Route::get('itens', 'EmularController@index');
        } );
    } );

} );
