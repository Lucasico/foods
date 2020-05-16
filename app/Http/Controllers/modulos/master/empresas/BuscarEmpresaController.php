<?php

namespace App\Http\Controllers\modulos\master\empresas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//razao social, cnpj, cidade, rua, bairro, numero, categoria
class BuscarEmpresaController extends Controller
{
   
    public function filtraEmpresa(Request $request){
     
      if(
          is_null(Request()->input('razao_social')) &&
          is_null(Request()->input('cnpj')) &&
          is_null(Request()->input('cidade')) &&
          is_null(Request()->input('rua')) &&
          is_null(Request()->input('numero')) &&
          is_null(Request()->input('bairro')) &&
          is_null(Request()->input('numero')) &&
          is_null(Request()->input('categoria')) 
        ){
        return response()->json(["ErrosValida" => "Nenhum campo de busca preenchido, por favor tente novamente"],200);
      }

      $query = DB::table('empresas')
      
      ->when(Request()->input('razao_social'), function($query){
        $query->where('razao_social', Request()->input('razao_social'));
      })

      ->when(Request()->input('cnpj'),function($query){
        $query->where('cnpj', Request()->input('cnpj'));
      })

      ->when(Request()->input('cidade'),function($query){
        $query->where('cidade', Request()->input('cidade'));
      })

      ->when(Request()->input('rua'),function($query){
        $query->where('rua', Request()->input('rua'));
      })

      ->when(Request()->input('numero'),function($query){
        $query->where('numero', Request()->input('numero'));
      })

      ->when(Request()->input('bairro'),function($query){
        $query->where('bairro', Request()->input('bairro'));
      })

      ->when(Request()->input('categoria'),function($query){
        $query->where('categoria',Request()->input('categoria'));
      })

      ->paginate(10);
      
      if($query->isEmpty()){
        return response()->json(["ErrosValida" => "Nenhuma Empresa encontrada!"],200);
      }

      return response()->json($query,200); 
    }
      
}


