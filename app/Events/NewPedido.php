<?php

namespace App\Events;

use App\Funcionarios;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Nyholm\Psr7\Request;

class NewPedido implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $teste;
    public $empresa;
    public function __construct($teste,$empresa)
    {
        $this->teste = $teste;
        $this->empresa = $empresa;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
//        //se o id do pedido por referente ao
//        $user = auth()->user()->id;
//        $funcionario = Funcionarios::where('user_id','=','$user')->first();
//        $empresaId = $funcionario->empresa_id;
//        if($empresaId = $this->empresa){
//            return new Channel('newPedidoEmpresa-'.$this->empresa);
//        }
        return new Channel('newPedidoEmpresa-'.$this->empresa);

    }
}
