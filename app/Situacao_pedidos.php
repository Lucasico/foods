<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Situacao_pedidos extends Model
{
    protected $table = 'situacao_pedidos';
    protected $fillable = [
        'nome',
    ];

    public function pedido()
    {
        return $this->hasMany(pedidos::class,'situacao_pedido_id','id');
    }
}
