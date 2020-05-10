<?php

namespace App;
//namespace  Illuminate\Database\Eloquent\SoftDeletes;
//use SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Produtos extends Model
{
    use Uuids;
    protected $table = 'produtos';
    public $incrementing = false;
    public $fillable = [
        'tipos_id',
        'empresas_id',
        'nome',
        'unidade_compra',
        'descricao',
        'precoVenda',
        'precoCompra',
        'quantEstoque',
        'quantMinina',
        'created_at',
        'updated_at'
    ];
    // um produto tem um tipo
    public function tipo(){
        return $this->belongsTo('App\Tipos','tipos_id','id');
    }
    //um produto pertence a uma empresa
    public function empresa(){
        return $this->belongsTo('App\Empresas','empresas_id','id');
    }
    
    public function composicao(){
        return $this->belongsToMany('App\Composicoes','Composicao_produtos','produto_id','composicao_id');
    }

}
