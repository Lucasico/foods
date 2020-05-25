<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cidades extends Model
{
    protected $table = 'cidades';
    public function estado(){
        return $this->belongsTo(Estados::class);
    }

    //uma cidade contem muitas empresas
    public function empresas(){
        return $this->hasMany(Empresas::class,'cidade_id','id');
    }

    //uma cidade contem muitas pessoas
    public function pessoa(){
        return $this->hasMany(Pessoas::class,'cidade_id','id');
    }
}
