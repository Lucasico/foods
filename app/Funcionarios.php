<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Funcionarios extends Model
{
    protected $table = 'funcionarios';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
        'empresa_id',
        'funcao_id',
        'user_id'
    ];

    public function funcao()
    {
        return $this->belongsTo(Funcoes::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresas::class);
    }


}
