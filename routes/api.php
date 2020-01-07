<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//use Illuminate\Routing\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('registro', 'AutentificadorController@registro');
    Route::post('login', 'AutentificadorController@login');

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', 'AutentificadorController@logout');
    });
});

Route::get('itens', 'EmularController@index')
    ->middleware('auth:api');
