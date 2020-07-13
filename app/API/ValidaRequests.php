<?php
namespace App\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidaRequests
{
    public static function validaCadastroEmpresa ( Request $request )
    {
        $data = $request->all ();
        $validacao = Validator::make ( $data , [
            'bairro' => 'required' ,
            'rua' => 'required' ,
            'cep' => 'required' ,
            'categoria' => 'required|string' ,
            'telefone' => 'required|min:8|max:11' ,
            'celular' => 'required|min:8|max:16' ,
            'situacao' => 'required' ,
            'razao_social' => 'required' ,
            'cnpj' => 'required|cnpj' ,
            'email' => 'email' ,
            'cidade_id' => 'required' ,
            'numero' => 'required'
        ] );
        if ( $validacao->fails () ) return response ()->json ( $validacao->errors () );
    }

    public static function validaAtualizaEmpresa ( Request $request )
    {
        $data = $request->all ();
        $validacao = Validator::make ( $data , [
            'bairro' => 'string' ,
            'rua' => 'string' ,
            'cep' => 'string' ,
            'categoria' => 'string' ,
            'telefone' => 'min:8|max:11' ,
            'celular' => 'min:8|max:16' ,
            'razao_social' => 'string' ,
            'cnpj' => 'cnpj' ,
            'email' => 'email' ,
            'numero' => 'int'
        ] );
        if ( $validacao->fails () ) {
            return response ()->json ( $validacao->errors () );
        }
    }

    public static function validaCadastroDotipoProprietario ( Request $request )
    {
        $data = $request->all ();
        $validacao = Validator::make ( $data , [
            'name' => 'required|string' ,
            'password' => 'required|string|confirmed' ,
            'email' => 'required|string|email|unique:users' ,
            'pessoas_id' => 'required|UUID|unique:users'
        ] );
        if ( $validacao->fails () ) {
            return response ()->json ( $validacao->errors () );
        }
    }

    public static function validaCadastroDePessoa ( Request $request )
    {
        $data = $request->all ();
        $validacao = Validator::make ( $data , [
            'nome' => 'required|string' ,
            'telefone' => 'required|string|min:9|max:16' ,
            'cidade_id' => 'required|numeric' ,
            'funcoes_id' => 'required|numeric' ,
            'permissao_id' => 'required|numeric' ,
            'password' => 'required|string|confirmed|min:6|max:10' ,
            'email' => 'required|string|email|unique:users' ,
            'rua' => 'required|string' ,
            'bairro' => 'required|string' ,
            'numero' => 'required|string'
        ] );
        if ( $validacao->fails () ) {
            return response ()->json ( $validacao->errors () );
        }
    }

    public static function validaAtualizaPessoa ( Request $request )
    {
        $data = $request->all ();
        $validacao = Validator::make ( $data , [
            'nome' => 'string' ,
            'telefone' => 'string|min:9|max:16' ,
            'situacao' => 'string' ,
            'email' => 'email' ,
            'restaurarSenhaPadrao' => 'required|string'
        ] );
        if ( $validacao->fails () ) {
            return response ()->json ( $validacao->errors () );
        }
    }

    public static function validaAtualizacaoUserProprietario ( Request $request )
    {
        $data = $request->all ();
        $validacao = Validator::make ( $data , [
            'password' => 'string|confirmed' ,
            'email' => 'string|email|unique:users'
        ] );
        if ( $validacao->fails () ) {
            return response ()->json ( $validacao->errors () );
        }
    }

    public static function validaCategoriaProduto( Request $request){
        $data = $request->all ();
        $validacao = Validator::make($data , [
          'nome' => 'required|string|unique:categorias'
        ] );
        if($validacao->fails () ){
            return response()->json( $validacao->errors() );
        }
    }

    public static function validaCategoriaProdutoAtualiza( Request $request){
        $data = $request->all ();
        $validacao = Validator::make($data , [
            'nome' => 'required|string'
        ] );
        if($validacao->fails () ){
            return response()->json( $validacao->errors() );
        }
    }

    public static function validaSubCategoriaCreate( Request $request){
        $data = $request->all ();
        $validacao = Validator::make($data , [
            'nome' => 'required|string',
            'categoria_id' => 'required',
        ] );
        if($validacao->fails () ){
            return response()->json( $validacao->errors() );
        }
    }

    public static function validaSubCategoriaUpdate( Request $request){
        $data = $request->all ();
        $validacao = Validator::make($data , [
            'nome' => 'required|string',
            'categoria_id' => 'required|numeric',
            'situacao' => 'required'
        ] );
        if($validacao->fails () ){
            return response()->json( $validacao->errors() );
        }
    }

    public static function validaIgredienteCreate( Request $request){
        $data = $request->all ();
        $validacao = Validator::make($data , [
            'nome_ingredientes' => 'required|string|unique:composicoes',
        ] );
        if($validacao->fails () ){
            return response()->json( $validacao->errors() );
        }
    }

    public static function validaIgredienteUpdate( Request $request){
        $data = $request->all ();
        $validacao = Validator::make($data , [
            'nome_ingredientes' => 'required|string',
        ] );
        if($validacao->fails () ){
            return response()->json( $validacao->errors() );
        }
    }

    public static function validaUpdateEmpresaProprietario( Request $request)
    {
        $data = $request->all ();
        $validacao = Validator::make ( $data , [
            'razao_social' => 'string|required',
            'bairro' => 'string|required',
            'rua' =>'string|required',
            'cep' => 'string|required',
            'taxaEntrega'=> 'required|numeric',
            'tempoEntrega'=> 'string|required',
            'telefone' => 'string|required|min:8|max:11' ,
            'celular' => 'string|required|min:8|max:16' ,
            'email'=> 'string|required|email',
            'instagram'=>'string|required',
            'numero'=> 'string|required',
        ] );
        if ( $validacao->fails () ) {
            return response ()->json ( $validacao->errors () );
        }
    }

    public static function validaCadastroDeFuncionario(Request $request)
    {
        $data = $request->all ();
        $validacao = Validator::make ( $data , [
            'password' => 'required|string|confirmed|min:6|max:15' ,
            'email' => 'required|string|email|unique:users' ,
            'nome' => 'required|string',
            'funcao_id' => 'required|numeric|min:3|max:3'
        ] );
        if ( $validacao->fails () ) {
            return response ()->json ( $validacao->errors () );
        }
    }

    public static function validaUpdateFuncionarioEmpresa(Request $request)
    {
        $data = $request->all ();
        $validacao = Validator::make ( $data , [
            'email' => 'required|string|email' ,
            'nome' => 'required|string',
            'situacao' => 'required|string'
        ] );
        if ( $validacao->fails () ) {
            return response ()->json ( $validacao->errors () );
        }
    }

    public static function validaCadastroDeProduto(Request $request)
    {
        $data = $request->all ();
        $validacao = Validator::make ( $data , [
            'sub_categoria' => 'required|numeric' ,
            'nome' => 'required|string',
            'descricao'=>'required|string',
            'preco'=>'required|numeric',
            'tipo'=>'required',
            'ingrediente'=>'numeric',
            'valor'=>'required|numeric'

        ] );
        if ( $validacao->fails () ) {
            return response ()->json ( $validacao->errors () );
        }
    }

}

?>
