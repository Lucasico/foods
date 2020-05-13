<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('registro', 'AutentificadorController@registro');
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

                //buscar proprietario
                Route::get('/{id}',
                'modulos\master\proprietarios\ProprietariosCrudController@show');
                
                //lista proprietarios
                Route::get('/proprietarios','modulos\master\proprietarios\ProprietariosCrudController@index');
                           
                //atualizar proprietario
                Route::put('/{id}',
                'modulos\master\proprietarios\ProprietariosCrudController@updateProprietario');
                
                //route para filtrar pessoas
                Route::post('/filtro',
                'modulos\master\proprietarios\BuscarProprietarioController@filtrarPessoaEmpresa')->name("filtro");


                //excluir proprietario
                //só vai funcionar quando atualizar as migrations, por conta da chave estrangeira
                //vai marca a linha como apagada
                Route::delete('proprietario/{id}','modulos\master\proprietarios\ProprietariosCrudController@deleteProprietario');
               
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
    

});






