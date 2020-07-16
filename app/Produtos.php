<?php

namespace App;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Produtos extends Model
{
    use Uuids;
    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'produtos';
    protected $fillable = [
        'empresa_id',
        'sub_categoria_id',
        'preco',
        'situacao',
        'tipo',
        'pertence_estoque',
        'tamanho',
        'unidade_compra',
        'descricao',
        'quantMinima',
        'quantEstoque',
        'nome'
    ];
    //
    public function sub_categoria()
    {
        return $this->belongsTo(Sub_categorias::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresas::class);
    }

    public function item_pedido()
    {
        return $this -> hasMany ( Item_pedidos::class , 'produto_id' , 'id' );
    }
    //Muito pra Muito
    public function composicao()
    {
        return $this->belongsToMany(Composicoes::class, 'composicao_produtos','produto_id','composicao_id')
            ->withPivot(['valor']);
    }
    //combo
    public function combo()
    {
        return $this->belongsToMany(Produtos::class, 'combos','combo_id','produto_id');
    }
}
