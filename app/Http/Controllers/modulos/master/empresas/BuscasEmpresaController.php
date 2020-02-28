<?php

namespace App\Http\Controllers\modulos\master\empresas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Empresas;
use App\API\ApiErros;
use Illuminate\Support\Facades\DB;

class BuscasEmpresaController extends Controller
{
    public function quantidadeEmpresas(){
        try{
            $countEmpresa = count(Empresas::all());
            return response()->json(['Quantidade' => $countEmpresa]);
        }catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1020));
            }
                 //para opção de produção
                return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao contar empresas',1020));
        }
    }

    public function EmpresasAtivas(){
        try{
            return response()->json(Empresas::where('situacao',true)->paginate(10),200);
        }catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1021));
            }
                 //para opção de produção
                return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao listar empresas ativas',1021));
        }
    }

    public function EmpresasInativas(){
        try{
            return response()->json(Empresas::where('situacao',false)->paginate(10),200);
        }catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1022));
            }
                 //para opção de produção
                return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao listar empresas inativas',1022));
        }
    }

    public function quantEmpresaAtiva(){
        try{
            $countEmpresa = count(Empresas::where('situacao',true)->get());
            $empresas = self::EmpresasAtivas();
            return response()->json([
                'Quantidade' => $countEmpresa,
                'Empresas' => $empresas
            ]);
        }catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1023));
            }
                 //para opção de produção
                return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao listar e contar empresas ativas',1023));
        }
    }
  
    public function quantEmpresaInativas(){
        try{
            $countEmpresa = count(Empresas::where('situacao',false)->get());
            $empresas = self::EmpresasInativas();
            return response()->json([
                'Quantidade' => $countEmpresa,
                'Empresas' => $empresas
            ]);
        }catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiErros::erroMensageCadastroEmpresa($e->getMessage(),1024));
            }
                 //para opção de produção
                return response()->json(ApiErros::erroMensageCadastroEmpresa('Houve um erro ao listar e contar empresas inativas',1024));
        }
    }
    
}
