<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permissoes extends Model
{
    protected $table = 'permissoes';

    protected $fillable = [
        'nome' ,
    ];

    public function user()
    {
        return $this->hasMany(User::class,'permissao_id','id');
    }
}
