<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avaliar_empresas extends Model
{
    protected $table = 'avaliar_empresas';
    protected $fillable = [
        'user_id',
        'empresa_id',
        'titulo',
        'descricao',
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
