<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Funcoes extends Model
{
    protected $table = 'funcoes';
    protected $fillable = [
       'nome'
    ];
    public function funcionario(){
        return $this->hasMany(Funcionarios::class,'funcao_id','id');
    }
}
