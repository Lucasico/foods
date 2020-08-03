<?php
namespace App\WebSockets;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use WebSocket;

// Incluindo biblioteca e classe do chat
//require 'vendor/autoload.php';
//require 'class/WebSocket.php';
// Iniciando conexão
class WebServer {
    // Iniciando conexão
    public function executar()
    {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new WebSocket()
                )
            ),
            1000
        );
        $server->run();
    }
}


