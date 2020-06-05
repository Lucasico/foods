<?php

namespace App;

use App\Traits\Uuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

//user a biblioteca abaixo
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    //use HasApiTokens
    use Notifiable , HasApiTokens;
    use Uuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $incrementing = false;
    protected $fillable = [
        'email' ,
        'password' ,
        'permissoes_id' ,
        'pessoas_id'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password' , 'remember_token' ,
    ];
    /**
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime' ,
    ];

    //um usuario tem uma permissão
    public function permissao ()
    {
        return $this -> belongsTo ( 'App\Permissoes' , 'permissoes_id' , 'id' );
    }

    // um usuario pertence a uma pessoa
    public function pessoa ()
    {
        return $this -> belongsTo ( Pessoas::class , 'pessoas_id' , 'id' );
    }
}
