<?php

namespace App;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Item_pedidos extends Model
{
    use Uuids;
    protected $table = 'item_pedidos';
    protected $fillable = [
        'produto_id',
        'pedido_id',
        'quantidade',
        'desconto',
        'valor',
    ];

    public function produto()
    {
        return $this->belongsTo(Produtos::class);
    }

    public function pedido()
    {
        return $this->belongsTo(pedidos::class);
    }
}
