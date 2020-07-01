<?php

namespace App;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Empresas extends Model
{
    use Uuids;
    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'empresas';
    protected $fillable = [
        'cidade_id',
        'razao_social',
        'cnpj',
        'situacao',
        'bairro',
        'rua',
        'cep',
        'taxaEntrega',
        'tempoEntrega',
        'categoria',
        'telefone',
        'celular',
        'telefone',
        'email',
        'instagram',
        'numero'
    ];

    public function cidade()
    {
        return $this->belongsTo(Cidades::class);
    }

    public function produto ()
    {
        return $this -> hasMany ( Produtos::class , 'empresa_id' , 'id' );
    }

    public function avaliar_empresa()
    {
        return $this->hasMany(Avaliar_empresas::class,'empresa_id','id');
    }

    public function avaliar_cliente()
    {
        return $this->hasMany(Avaliar_clientes::class,'empresa_id','id');
    }

    public function funcionario()
    {
        return $this->hasMany(Funcionarios::class,'empresa_id','id');
    }


}
