<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Combos extends Model
{
    //
    protected $table = 'combos';
    public $fillable = ['nome','produto_id','combo_id'];
    public $timestamps = false;
}
