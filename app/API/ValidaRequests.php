<?php
namespace App\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidaRequests{
    public static function validaCadastroEmpresa(Request $request){
        $data = $request->all();
        $validacao = Validator::make($data,[
            'cidade' => 'required',
            'bairro' => 'required',
            'rua' => 'required',
            'cep' => 'required',
            'categoria' => 'required|string',
            'telefone' => 'required|min:8|max:11',
            'celular' => 'required|min:8|max:16',
            'situacao' => 'required',
            'razao_social' => 'required',
            'cnpj' => 'required|cnpj',
            'email' => 'email',
            'numero' => 'required'
       ]);
       if($validacao->fails()){
         return response()->json($validacao->errors());
       }
    }

    public static function validaAtualizaEmpresa(Request $request){
      $data = $request->all();
        $validacao = Validator::make($data,[
            'cidade' => 'string',
            'bairro' => 'string',
            'rua' => 'string',
            'cep' => 'string',
            'categoria' => 'string',
            'telefone' => 'min:8|max:11',
            'celular' => 'min:8|max:16',
            'razao_social' => 'string',
            'cnpj' => 'cnpj',
            'email' => 'email',
            'numero' => 'int'
       ]);
       if($validacao->fails()){
         return response()->json($validacao->errors());
       }
    }
}
?>