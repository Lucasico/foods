<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//namespace  Illuminate\Database\Eloquent\SoftDeletes;
//use SoftDeletes;
class Combos extends Model
{
    //
    protected $table = 'combos';
    public $fillable = ['nome','produto_id','combo_id'];
    public $timestamps = false;
}
