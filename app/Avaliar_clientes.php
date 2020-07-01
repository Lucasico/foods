<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avaliar_clientes extends Model
{
    protected $table = 'avaliar_clientes';
    protected $fillable = [
        'users_id',
        'empresa_id',
        'situacao_cliente',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresas::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
