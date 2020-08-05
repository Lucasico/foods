<?php
require 'vendor/autoload.php';
//namespace App\WebSockets;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

use App\WebSockets\WebSocket;
// Incluindo biblioteca e classe do chat
//require 'class/WebSocket.php';
// Iniciando conexão
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new WebSocket()
                )
            ),
            1000
        );
        $server->run();


