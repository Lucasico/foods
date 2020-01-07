<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class AutentificadorController extends Controller
{
    public function registro(Request $request)
    {
        //parte de validadação
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string|confirmed',
            'email' => 'required|string|email|unique:users'
        ]);
        //criando o usuario
        $user = new User([
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'email' => $request->email
        ]);
        //salvando
        $user->save();

        return response()->json([
            'res'=>'Usuario criado com sucesso'
        ],201)

    }

    public function login(Request $request)
    {
    }

    public function logout(Request $request)
    {
    }
}
