<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('registro', 'AutentificadorController@registro')->name("registro");
    Route::get('index','AutentificadorController@index');
    Route::post('login', 'AutentificadorController@login');

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', 'AutentificadorController@logout');
        //Group routes mastes
        Route::middleware('master')->group(function(){
           //dashboard
            Route::prefix('dataDashboard')->group(function(){        
                //quantidade de empresa
                Route::get('/dashboard/quantidadeEmpresas','modulos\master\dashboard\DashboardMasterController@quantidadeEmpresas');
                 //quantidade de cliente
                Route::get('/dashboard/quantidadeClientes','modulos\master\dashboard\DashboardMasterController@quantidadeClientes');
                 //quantidade de produtos
                 Route::get('/dashboard/quantidadeProdutos','modulos\master\dashboard\DashboardMasterController@quantidadeProdutos');
            });
           
            //routes de empresas
            Route::prefix('empresas')->group(function(){
                /**
                 * parte basica de empresa CRUD
                 */
                //lista
                Route::get('/','modulos\master\empresas\EmpresasCrudController@index');
                
                //buscar um registro
                Route::get('/{id}','modulos\master\empresas\EmpresasCrudController@show');
                
                //criando empresa
                Route::post('/','modulos\master\empresas\EmpresasCrudController@store');
               
                //atualizando uma empresa
                Route::put('/{id}','modulos\master\empresas\EmpresasCrudController@update');
                
                //excluindo uma empresa
                Route::delete('/{id}','modulos\master\empresas\EmpresasCrudController@delete');

                //filtrarEmpresa
                Route::post('/filtrar','modulos\master\empresas\BuscarEmpresaController@filtraEmpresa');
               
            });

            //routes de proprietarios
            Route::prefix('proprietario')->group(function(){
                //criando PessoaProprietaria
                Route::post('/',
                'modulos\master\proprietarios\ProprietariosCrudController@storePessoaProprietaria');

                //listagem de pessoas para cadastro de usuario
                Route::get('/pessoas',
                'modulos\master\proprietarios\ProprietariosCrudController@retornaPessoaParaCadastroDeUsuario');

                //listagem de empresas aptas a receber cadastro de pessoas
                Route::get('/empresas',
                'modulos\master\proprietarios\ProprietariosCrudController@retornaEmpresasParaCadastroDePessoa');

                //criar UsuarioProprietario 
                Route::post('/cadUserProprietaria',
                'modulos\master\proprietarios\ProprietariosCrudController@storeUserProprietario');

                //
                Route::get('/listagem',
                'modulos\master\proprietarios\ProprietariosCrudController@listagem')->name("listagem");

                // // //buscar proprietario
                //  Route::get('/buscar/{id}',
                //  'modulos\master\proprietarios\ProprietariosCrudController@buscarUmProprietario');
                        
                //atualizar pessoa proprietario
                Route::put('/{id}',
                'modulos\master\proprietarios\ProprietariosCrudController@updateProprietario');

                //atualizar User proprietario
                Route::put('/user/{id}',
                'modulos\master\proprietarios\ProprietariosCrudController@updateUserProprietario');
                
                //route para filtrar pessoas
                Route::post('/filtro',
                'modulos\master\proprietarios\BuscarProprietarioController@filtrarPessoaEmpresa')->name('filtra proprietario');

                //route para excluir um proprietario
                Route::delete('/{id}','modulos\master\proprietarios\ProprietariosCrudController@deleteProprietario');

                
               
            });
            Route::prefix('clientes')->group(function(){
                //route para filtragem de clientes
                Route::post('/','modulos\master\clientes\ListClienteContrller@filtratListaCliente')->name("fitragem de clientes");
                //route listagem de clientes
                Route::get('/lista','modulos\master\clientes\ListClienteContrller@listagemClientes')->name("listaClientes");
            });

            //lista referente a usuarios Master
            Route::prefix('UsersMasters')->group(function(){
                Route::post('/','modulos\master\profiles\ProfilesCrudMasters@storeUserMaster');
                Route::post('/cadPessoMaster','modulos\master\profiles\ProfilesCrudMasters@storePessoaMaster');
            });

        });

        //Group routes proprietario
        Route::middleware('proprietario')->group(function(){
            //route para cadastro de tipo
            Route::post('/cadTipoProduto','modulos\proprietario\produtos\ProdutoCrudController@storeTiposProduto');
            //route para cadastro de produto por empresas
            Route::post('/cadProdutoEmpresa','modulos\proprietario\produtos\ProdutoCrudController@storeProdutoEmpresa');
            //route para lista de produtos por empresas
            Route::get('/listaProdutoEmpresa','modulos\proprietario\produtos\ProdutoCrudController@index');
            //route para atualizar um produto por empresa
            Route::put('/atualizaProdutoEmpresa/{id}','modulos\proprietario\produtos\ProdutoCrudController@updateProduto');
            //route para exibir um produto
            Route::get('/produtoEmpresa/{id}','modulos\proprietario\produtos\ProdutoCrudController@showProduto');
            //route para excluir um produto
            Route::delete('/excluirProduto/{id}','modulos\proprietario\produtos\ProdutoCrudController@deleteProduto');
            //route para inserir produto no combo
            Route::post('/comboProduto/{id}','modulos\proprietario\produtos\produtoCrudController@createComboProdutos');
            
        });

        //Group routes "funcionario"
        //atençaõ para combinação de permissão + função para definir function
        Route::middleware('funcionario')->group(function(){
           // Route::get('itens', 'EmularController@index');
        });

        //Group route para cliente
        Route::middleware('cliente')->group(function(){
           // Route::get('itens', 'EmularController@index');
        });
    });
    
//comentariiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii
});
