<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|json
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
           // return response()->json("Não",400);
            return route('invalido');
        }
    }
}
