<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cidades extends Model
{
    protected $table = 'cidades';

    public function estado ()
    {
        return $this -> belongsTo ( Estados::class );
    }

    public function user()
    {
        return $this->hasMany(User::class,'cidade_id','id');
    }

    public function empresa()
    {
        return $this->hasMany(Empresas::class,'cidade_id','id');
    }

}
