<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AutentificadorController extends Controller
{
    public function registro(Request $request)
    {
        $emailCadastrado = DB::table('users')->where('email', $request->email)->value('email');
        $erroCadastroEmail = "E-mail ja cadastrado";

        if ($emailCadastrado != null) {
            return response()->json(
                $erroCadastroEmail
            );
        } else {
            //parte de validadação
            $request->validate([
                'password' => 'required|string|confirmed',
                'email' => 'required|string|email|unique:users'
            ]);
            //criando o usuario
            $user = new User([
                'name'=>$request->name,
                'password' => bcrypt($request->password),
                'email' => $request->email,
                'pessoas_id' => $request->pessoas_id,
                'permissoes_id' => $request->permissoes_id
            ]);
            //salvando
            $user->save();
            return response()->json([
                'res' => 'Usuario criado com sucesso'
            ], 201);
        }
    }
    public function login(Request $request)
    {
    	 if ($request->email == "" or $request->password == "") {
            if ($request->email == "") {
                return response()->json([
                    'email' => "Email Inválido"
                ], 401);
            }
            if ($request->password == "") {
                return response()->json([
                    'password' => "Senha inválida"
                ], 401);
            }
            
        }

        $request->validate([
            'password' => 'required|string',
            'email' => 'required|string|email'
        ]);

        $credencias = [
            'password' => $request->password,
            'email' => $request->email
        ];

        //retorna um true ou false
        if (!Auth::attempt($credencias)) {
             return response()->json([
                'res' => 'Email ou senha inválido(s)'
            ], 401);
        }
        //criando token de validação de usuario
        $user = $request->user();
        $token = $user->createToken('Token de acesso')->accessToken;
        //retornando token de acesso com a devida confirmação
        return response()->json([
            'token' => $token,
            'user'=> $user
        ], 200);
    }

    public function logout(Request $request)
    {
        //revogando token com o revoke
        $request->user()->token()->revoke();
        return response()->json([
            'res' => 'Deslogado com sucesso'
        ], 200);
    }
    /**
     * public function index(){
     *$produto = Produtos::find('bfd9b59b-244c-4c0a-a293-0cbee647c11b');
     *$produtoTipo = $produto->tipo;
     *$produtoEmpresa = $produto->empresa;
      *return response()->json($produto);
    *}
     * 
     */
    
}
