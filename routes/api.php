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
            
            //lista de empresas
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
                /**
                 * Parte complementar de empresa 
                 * quantidade de empresa
                 * quantidade de empresas ativas, nomes
                 * quantidade de empresa inativas, nomes
                 * 
                 */
                Route::get('totalEmpresas','modulos\master\empresas\BuscasEmpresaController@quantidadeEmpresas');
                Route::get('totalEmpresas/ativas','modulos\master\empresas\BuscasEmpresaController@quantEmpresaAtiva');
                Route::get('totalEmpresas/inativas','modulos\master\empresas\BuscasEmpresaController@quantEmpresaInativas');
               
            });

            //lista referente a proprietarios
            Route::prefix('proprietario')->group(function(){
                //criando PessoaProprietaria
                Route::post('/cadPessoProprietaria',
                'modulos\master\proprietarios\ProprietariosCrudController@storePessoaProprietaria');

                //criar proprietario 
                Route::post('/cadUserProprietaria',
                'modulos\master\proprietarios\ProprietariosCrudController@storeUserProprietario');

                //buscar proprietario
                Route::get('/proprietario/{id}',
                'modulos\master\proprietarios\ProprietariosCrudController@show');
                
                //lista proprietarios
                Route::get('/proprietarios','modulos\master\proprietarios\ProprietariosCrudController@index');
                           
                //atualizar proprietario
                Route::put('/proprietario/{id}',
                'modulos\master\proprietarios\ProprietariosCrudController@updateProprietario');
               
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
            //route para teste de cadastro de produto
            Route::post('/cadProdutoEmpresa','modulos\proprietario\produtos\ProdutoCrudController@storeProdutoEmpresa');
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






