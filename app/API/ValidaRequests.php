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
          'sexo' => 'required|min:1|max:1',
          'telefone' => 'required|string|min:9|max:16',
          'cidade'=> 'required|string',
          'rua'=> 'required|string',
          'cep'=> 'required|string',
          'bairro'=> 'required|string',
          'cpf' => 'required|cpf|min:14|max:14|unique:pessoas',
          'empresas_id' => 'required|UUID'
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