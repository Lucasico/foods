<?php
namespace App\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidaRequests{
    public static function validaCadastroProduto(Request $request){
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
            'email' => 'email'
       ]);
       if($validacao->fails()){
         return response()->json($validacao->errors());
       }
    }
}
?>