<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Formas_pagamentos extends Model
{
    protected $table = 'formas_pagamentos';
    protected $fillable = [
        'nome',
    ];

    public function pedido()
    {
        return $this->hasMany(pedidos::class,'forma_pagamento_id','id');
    }
}
