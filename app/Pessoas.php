<?php

namespace App;
//namespace  Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
//use SoftDeletes;

class Pessoas extends Model
{
    use Uuids;
    public $incrementing = false;
    protected $table = 'pessoas';
    public $timestamps = false;

    public $fillable = [
        'nome',
        'empresas_id',
        'funcoes_id',
        'telefone',
        'cidade_id'
    ];

    //1 para 1
    public function users(){
        //uma pessoa tem um usuario
        return $this->hasOne('App\users');
    }

    //1 para n
    public function funcao(){
        //uma pessoa tem uma funcao
        return $this->belongsTo('App\Funcoes', 'funcoes_id', 'id');
     }

     //1 para n
     public function empresa(){
         //1 pessoa esta numa empresa
         return $this->belongsTo('App\Empresas','empresas_id','id');
     }
    //1 para n
     public function cidade(){
        return $this->belongsTo(Cidades::class);
     }

}
