<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use ChatPhp\Chat\Socket;

$configPath = './config.php';
if (isset($argv[1])) {
	$configPath = $argv[1];
}

require './vendor/autoload.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Socket($configPath)
        )
    ),
    8080
);

$server->run();
