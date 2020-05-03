<?php

namespace App\Http\Controllers\modulos\master\dashboard;

use App\Http\Controllers\Controller;
use App\Empresas;
use App\User;
use App\Produtos;
use App\API\ApiErros;

class DashboardMasterController extends Controller
{
    public function quantidadeEmpresas(){
        try{
            $countEmpresa = count(Empresas::all());
            return response()->json(['Quantidade de empresas: ' => $countEmpresa],200);
        }catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1022));
            }
                 //para opção de produção
                return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao contar empresas',1022));
        }
    }

    public function quantidadeClientes(){
        try{
            $quantCliente = User::where('permissoes_id',4)->count();
            return response()->json(['Quantidade de clientes:' => $quantCliente],200);
        }catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1023));
            }
                 //para opção de produção
                return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao contar clientes',1023));
        }
    }

    public function quantidadeProdutos(){
        try{
            $quantProdutos = count(Produtos::all());
            return response()->json(['Quantidade de produtos' => $quantProdutos],200);
        }catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1024));
            }
                 //para opção de produção
                return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao contar clientes',1024));
        }
    }
}
