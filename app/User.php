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
        'permissao_id',
        'password',
        'cidade_id',
        'email',
        'senha',
        'nome',
        'telefone',
        'rua',
        'bairro',
        'numero',

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

    public function permissao()
    {
        return $this->belongsTo(Permissoes::class);
    }

    public function cidade()
    {
        return $this->belongsTo(Cidades::class);
    }

    public function avaliar_empresa()
    {
        return $this->hasMany(Avaliar_empresas::class,'user_id','id');
    }

    public function avaliar_cliente()
    {
        return $this->hasMany(Avaliar_clientes::class,'users_id','id');
    }

    public function pedido()
    {
        return $this->hasMany(pedidos::class,'user_id','id');
    }

    public function funcionario()
    {
        return $this->hasOne(Funcionarios::class,'user_id','id');
    }


}
