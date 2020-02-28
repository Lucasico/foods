<?php

namespace App;
//namespace  Illuminate\Database\Eloquent\SoftDeletes;
//use SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Empresas extends Model
{
    protected $table = 'empresas';
    use Uuids;
    public $incrementing = false;
    public $timestamps = false;
    public $fillable = [
        'razao_social',
        'nomeProprietario',
        'cnpj',
        'situacao',
        'cidade',
        'bairro',
        'rua',
        'cep',
     ];
     //tem n pessoas
     public function pessoas(){
         return $this->hasMany('App\Pessoas');
     }
     //uma empresa tem muitos produtos
     public function produtos(){ 
         
        return $this->hasMany('App\Produtos');
    }
}
