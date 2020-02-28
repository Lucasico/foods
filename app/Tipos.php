<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tipos extends Model
{
    protected $table = 'tipos';
    public $timestamps = false;

    public $fillable = ['id','tipo'];
    //uma tipo tem muitos produto
    public function produtos(){ 
        return $this->hasMany('App\Produtos');
    }
}
