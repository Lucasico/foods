<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Composicoes extends Model
{
    protected $table = 'composicoes';
    protected $fillable = [
        'nome_ingredientes',
        'categoria_composicao'
    ];
    public function produtos()
    {
        return $this->belongsToMany(Produtos::class, 'composicao_produtos','composicao_id','produto_id');
    }
}
