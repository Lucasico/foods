<?php

namespace App\Http\Middleware;

use Closure;

class VerificaFuncionario
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $userPermissao = $request->user()->permissao_id;
        $retornoPermissaoInvalida = "Usuario com acesso inválido";

        if($userPermissao == 3){
            return $next($request);
        }
        return response()->json(["Data" => $retornoPermissaoInvalida],401);
    }
}
