<?php

namespace App;
namespace  Illuminate\Database\Eloquent\SoftDeletes;
//use SoftDeletes;
//use Illuminate\Database\Eloquent\Model;

class Composicoes extends Model
{
    protected $table = 'composicoes';
    public $timestamps = false;

    public $fillable = ['id','nome_ingridiente','categoria_composicao'];

    public function produto(){
        return $this->belongsToMany('App\Produtos','Composicao_produtos','composicao_id','produto_id');
    }
    
}
