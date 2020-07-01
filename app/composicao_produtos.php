<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class composicao_produtos extends Model
{
    protected $table = 'composicao_produtos';
    protected $fillable = [
        'valor',
    ];
}
