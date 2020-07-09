<?php

namespace App\API;

use Illuminate\Http\Request;

class BuscarEmpresa
{
    public static function BuscarEmpresa(Request $request)
    {
        $user = $request->user();
        $funcionario = $user->funcionario()->first();
        $empresa_id = $funcionario->empresa_id;
        return $empresa_id;
    }
}
