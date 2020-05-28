<?php

namespace App\Http\Controllers\modulos\master\util;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Estados;
use App\Cidades;

class ControllerUfCidade extends Controller
{
    public function listEstados(){
        return response()->json(['data'=>Estados::all()],200);
    }

    public function cidadesDoEstado(Estados $estado){
        $estado = Estados::find($estado);
        foreach ($estado as $c ){
            $cidades = $c->cidade()->get();
        }
        return response()->json(['data'=>$cidades],200);
    }
}
