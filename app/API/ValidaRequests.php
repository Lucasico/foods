<?php
namespace App\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidaRequests{
    public static function validaCadastroEmpresa(Request $request){
        $data = $request->all();
        $validacao = Validator::make($data,[
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
            'cidade_id'=>'required',
            'numero' => 'required'
       ]);
       if($validacao->fails()){
         return response()->json($validacao->errors());
       }
    }

    public static function validaAtualizaEmpresa(Request $request){
      $data = $request->all();
        $validacao = Validator::make($data,[
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

    public static function validaCadastroDotipoProprietario(Request $request){
      $data = $request->all();
        $validacao = Validator::make($data,[
          'name' => 'required|string',
          'password' => 'required|string|confirmed',
          'email' => 'required|string|email|unique:users',
          'pessoas_id' => 'required|UUID|unique:users'
       ]);
       if($validacao->fails()){
         return response()->json($validacao->errors());
       }
    }

    public static function validaCadastroDePessoa(Request $request){
      $data = $request->all();
        $validacao = Validator::make($data,[
            'nome' => 'required|string',
            'telefone' => 'required|string|min:9|max:16',
            'cidade_id'=> 'required|numeric',
            'funcoes_id'=>'required|numeric',
            'permissao_id'=>'required|numeric',
            'password' => 'required|string|confirmed|min:6|max:10',
            'email' => 'required|string|email|unique:users',
            'rua'=>'required|string',
            'bairro'=>'required|string',
            'numero'=>'required|string'
       ]);
       if($validacao->fails()){
         return response()->json($validacao->errors());
       }
    }

    public static function validaAtualizaPessoa(Request $request){
      $data = $request->all();
        $validacao = Validator::make($data,[
            'nome' => 'string',
            'telefone' => 'string|min:9|max:16',
            'password' => 'required|string|confirmed|min:6|max:10',
            'situacao' => 'string',
            'email' => 'email',
       ]);
       if($validacao->fails()){
         return response()->json($validacao->errors());
       }
    }

    public static function validaAtualizacaoUserProprietario(Request $request){
      $data = $request->all();
        $validacao = Validator::make($data,[
          'password' => 'string|confirmed',
          'email' => 'string|email|unique:users'
       ]);
       if($validacao->fails()){
         return response()->json($validacao->errors());
       }
    }
}
?>
