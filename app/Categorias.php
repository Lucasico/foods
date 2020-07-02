<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    protected $table = 'categorias';
    public $timestamps = false;
    protected $fillable = [
        'nome',
        'situacao'
    ];

    public function sub_categoria()
    {
        return $this -> hasMany ( Sub_categorias::class , 'categoria_id' , 'id' );
    }
}
