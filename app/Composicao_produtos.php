<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Composicao_produtos extends Model
{
    protected $table = 'Composicao_produtos';
    public $fillable = [ 'produto_id' , 'composicao_id' ];

}
