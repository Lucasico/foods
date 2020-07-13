<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class combos extends Model
{
    protected $table = 'combos';
    public $timestamps = false;
    protected $fillable = [
        'produto_id',
        'combo_id'
    ];
}
