<?php

namespace App;

use App\Traits\Increment;
use Illuminate\Database\Eloquent\Model;

class pedidos extends Model
{
    use Increment;
    public $incrementing = false;
    protected $table = 'pedidos';
    protected $fillable = [
        'situacao_pedido_id',
        'forma_pagamento_id',
        'user_id',
        'observacao',
    ];

    public function item_pedido()
    {
        return $this -> hasMany ( Item_pedidos::class , 'pedido_id' , 'id' );
    }

    public function situacao_pedido()
    {
        return $this->belongsTo(Situacao_pedidos::class);
    }

    public function forma_pagamento()
    {
        return $this->belongsTo(Formas_pagamentos::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //
}
