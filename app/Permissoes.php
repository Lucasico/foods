<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permissoes extends Model
{
    protected $table = 'permissoes';
    public $timestamps = false;

    public $fillable = ['id', 'nome'];

    //uma permissão tem muitos usuario
    public function users(){ 
        return $this->hasMany('App\User');
    }
}
