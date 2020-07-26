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

                //excluindo ucidadesDoEstadoma empresa
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
                Route ::get('/situacao/{categoria}','modulos\master\categoriaProdutos\CategoriaCrudController@desativarCategoria')->name('desativaSituacao');
                Route ::post('/filtrar','modulos\master\categoriaProdutos\BuscarCategoriaController@buscarCategoria')->name('buscarCategoria');
            });

            //routes de proprietarios
            Route ::prefix ( 'proprietario' ) -> group ( function () {

                //atualizar proprietario e usuario
                Route ::put ( '/atualizar/{pessoas}' , 'modulos\master\proprietarios\ProprietariosCrudController@alterarProprietarioUsuario' )
                    -> name ( 'atualizarPessoaUsuario' );

                //alterar Situação de proprietario
                Route ::get ( '/situacao/{user}' , 'modulos\master\proprietarios\ProprietariosCrudController@alterSituacaoProprietario' )
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
            Route::prefix('produtos') -> group( function () {
                //routes para categorias em proprietario para cadastro de sub-categorias
                Route::prefix('categoriaProdutos') -> group(function (){
                    Route ::get('/lista','modulos\master\categoriaProdutos\CategoriaCrudController@index')->name('listaCategoriasProp');
                    Route ::get('/{id}','modulos\master\categoriaProdutos\CategoriaCrudController@show')->name('exibirCategoriaProp');
                    Route ::post('/filtrar','modulos\master\categoriaProdutos\BuscarCategoriaController@buscarCategoria')->name('buscarCategoriaProp');
                });
                //routes para subcategoria de produtos
                Route::prefix('subCategoria') ->group(function (){
                    Route::get('/listar','modulos\proprietario\produtos\subCategoria\SubCategoriaCrudController@index')->name('listarSubCategorias');
                    Route::post('/cadastrar','modulos\proprietario\produtos\subCategoria\SubCategoriaCrudController@Store')->name('cadastroSubCategoria');
                    Route::get('/{id}','modulos\proprietario\produtos\subCategoria\SubCategoriaCrudController@show')->name('exibirSubCategoria');
                    Route::put('/atualizar/{subCategoria}','modulos\proprietario\produtos\subCategoria\SubCategoriaCrudController@update')->name('atualizarSubCategoria');
                    Route::get('/desativar/{subCategorias}','modulos\proprietario\produtos\subCategoria\SubCategoriaCrudController@desativarCategoria')->name('desativarSubCategoria');
                    Route::post('/buscar','modulos\proprietario\produtos\subCategoria\BuscarSubCategoriaController@buscarSubCategoria')->name('buscarSubCategoria');
                });
                //ingredientes ou adicionais de produto
                Route::prefix('ingredientes')->group(function (){
                    Route::get('/listar','modulos\proprietario\produtos\ingredientes\ingredientesCrudController@index')->name('listarIngredientes');
                    Route::post('/cadastrar','modulos\proprietario\produtos\ingredientes\ingredientesCrudController@Store')->name('cadastroIngredientes');
                    Route::get('/{ingrediente}','modulos\proprietario\produtos\ingredientes\ingredientesCrudController@show')->name('exibirIngredientes');
                    Route::put('/atualizar/{ingrediente}','modulos\proprietario\produtos\ingredientes\ingredientesCrudController@update')->name('atualizarIngrediente');
                    Route::post('/buscar','modulos\proprietario\produtos\ingredientes\BuscarIngredienteController@buscarIngredientes')->name('buscarIgredientes');
                    Route::delete('/excluir/{ingrediente}','modulos\proprietario\produtos\ingredientes\ingredientesCrudController@delete')->name('deletarIngrediente');
                });

                Route::post('/cadastrar','modulos\proprietario\produtos\ProdutoCrudController@create')->name('criandoProduto');
                Route::post('/cadastrar/combo','modulos\proprietario\produtos\ProdutoCrudController@createCombo')->name('criandoCombo');
                Route::get('/buscar','modulos\proprietario\produtos\ProdutoCrudController@listarProdutosDisponiveisParaCombo')->name('buscandoProdutoParaCombo');
                Route::get('/adicionais/buscar','modulos\proprietario\produtos\ingredientes\BuscarIngredienteController@buscarIngredientes')->name('buscarAdicionais');
                Route::get('/subcategorias/{categoria}','modulos\proprietario\produtos\ProdutoCrudController@subCategorias')->name('subCategorias');
                Route::get('/ingredientes','modulos\proprietario\produtos\ProdutoCrudController@listarIngredientes')->name('ingredientes');
                Route::get('/disponiveis','modulos\proprietario\produtos\ProdutoCrudController@listagemDeProdutosSemSelect')->name('produtosDisponiveis');
                Route::get('/cadastrados','modulos\proprietario\produtos\ProdutoCrudController@exibirProdutos')->name('cadastrados');
                Route::get('/desativar/{produto}','modulos\proprietario\produtos\ProdutoCrudController@desativarProduto')->name('desativarProduto');
                Route::get('/exibir/{produto}','modulos\proprietario\produtos\ProdutoCrudController@exibirProduto')->name('exibirProduto');
                Route::post('/filtrar','modulos\proprietario\produtos\ProdutoCrudController@filtrarProduto')->name('filtrarProduto');
                Route::put('/atualizar/{produto}','modulos\proprietario\produtos\ProdutoCrudController@editarProduto')->name('editarProduto');
            });
            //routes para gerenciamento de empresa de proprietario
            Route::prefix('empresa')->group(function (){
                Route::get('/exibir','modulos\proprietario\EmpresaController@exibirEmpresa')->name('mostrarEmpresaDoProprietario');
                Route::post('/funcionamento','modulos\proprietario\EmpresaController@habilitarDesabilitarFuncionamento')->name('horarioFuncionamento');
                Route::put('/atualizar','modulos\proprietario\EmpresaController@update')->name('atualizarEmpresaPatrao');
                Route::post('/cadastrar/funcionario','modulos\proprietario\EmpresaController@cadastrarFuncionario')->name('cadastroFuncionario');
                Route::get('/funcionarios','modulos\proprietario\funcionarios\FuncionariosController@funcionarioEmpresa')->name('teste');
                Route::put('/funcionario/atualizar/{funcionario}','modulos\proprietario\funcionarios\FuncionariosController@updateFuncionario')->name('atualizarFuncionario');
                Route::get('/funcionario/inativar/{user}','modulos\proprietario\funcionarios\FuncionariosController@desativarFuncionario')->name('inabilitarFuncionario');
                Route::get('/funcionario/exibir/{user}','modulos\proprietario\funcionarios\FuncionariosController@exibirFuncionario')->name('exibirFuncionario');
                Route::delete('/funcionario/excluir/{user}','modulos\proprietario\funcionarios\FuncionariosController@deleteFuncionario')->name('excluirFuncionario');
                Route::prefix('pedidos')->group(function (){
                    Route::get('/emAndamento','modulos\pedidos\PedidosController@listaPedidosEmAndamento')->name('emAndamento');
                    Route::get('/finalizados','modulos\pedidos\PedidosController@listaPedidosFinalizados')->name('finalizados');
                    Route::get('/situacoes','modulos\pedidos\PedidosController@situacoesPedidos')->name('situacoesPedidos');
                    Route::get('/atualizar/{pedido}','modulos\pedidos\PedidosController@alterarSituacaoPedido')->name('alterarSituacaoPedido');
                    Route::get('/{ped}','modulos\pedidos\PedidosController@visualizarPedidoCompleto')->name('visualizarPedidoCompleto');
                    Route::post('/buscar','modulos\pedidos\PedidosFiltrarController@filtrarPedidos')->name('filtrarPedidos');
                    Route::get('/cancelar/{pedido}','modulos\pedidos\PedidosController@cancelarPedido')->name('cancelarPedido');
                    Route::get('/naCozinha/{pedido}','modulos\pedidos\PedidosController@aceitarPedido')->name('aceitarPedido');
                });
            });
        } );

        //Group routes "funcionario"
        //atençaõ para combinação de permissão + função para definir function
        Route ::middleware ( 'funcionario' ) -> group ( function () {
            // Route::get('itens', 'EmularController@index');
        } );

        //Group route para cliente
        Route ::middleware ( 'cliente' ) -> group ( function () {
            Route::prefix('CestaDeProdutos')->group(function (){
                Route::post('/adicionarItems','modulos\pedidos\PedidosController@criarPedido')->name('criarPedido');
            });
        } );
    } );

} );
