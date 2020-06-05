<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estados extends Model
{
    public function cidade ()
    {
        return $this -> hasMany ( Cidades::class , 'estado_id' , 'id' );
    }
}
