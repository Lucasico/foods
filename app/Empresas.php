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
        'cnpj',
        'situacao',
        'bairro',
        'rua',
        'cep',
        'taxaEntrega',
        'tempoEntrega',
        'categoria',
        'telefone',
        'celular',
        'email',
        'instagram',
        'numero',
        'cidade_id'
     ];
     //tem n pessoas
     public function pessoas(){
         return $this->hasMany(Pessoas::class,'empresas_id','id');
     }

     //uma empresa tem muitos produtos
     public function produtos(){
        return $this->hasMany('App\Produtos');
    }

    public function cidade(){
         return $this->belongsTo(Cidades::class);
    }

}
