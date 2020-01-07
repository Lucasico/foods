<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    }

    public function login(Request $request)
    {
    }

    public function logout(Request $request)
    {
    }
}
