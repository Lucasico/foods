<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Funcoes extends Model
{
    protected $table = 'funcoes';
    public $timestamps = false;

    public $fillable = [ 'id' , 'nome' ];

    //uma função tem muitos pessoas
    public function pessoas ()
    {
        return $this -> hasMany ( 'App\Pessoas' );
    }

}
