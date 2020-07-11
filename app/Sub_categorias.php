<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Sub_categorias extends Model
{
    protected $table = 'sub_categorias';
    public $timestamps = false;
    protected $fillable = [
        'categoria_id',
        'nome',
        'situacao',
        'empresa_id'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categorias::class);
    }

    public function produto ()
    {
        return $this -> hasMany ( Produtos::class , 'sub_categoria_id' , 'id' );
    }
}
