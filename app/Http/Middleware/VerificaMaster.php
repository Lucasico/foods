<?php

namespace App\Http\Middleware;

use Closure;

class VerificaMaster
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

        if($userPermissao == 1){
            return $next($request);
        }
        return response()->json(["Data" => $retornoPermissaoInvalida],401);
    }
}
