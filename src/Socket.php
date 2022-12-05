<?php

namespace ChatPhp\Chat;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use ChatPhp\Chat\DB\Chat;
use ChatPhp\Chat\Config;

class Socket implements MessageComponentInterface {

    private $config;

    public function __construct($configPath = './config.php')
    {
        $this->clients = new \SplObjectStorage;
        $this->config = new Config($configPath);
    }

    public function onOpen(ConnectionInterface $conn) {

        // Store the new connection in $this->clients
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        if (!$this->isJson($msg)) {
            return;
        }

        $message = json_decode($msg);
        
        if (!empty($message->user_id)) {
            if (!isset($_SESSION['chat_users']) || !is_array($_SESSION['chat_users'])) {
                $_SESSION['chat_users'] = [];
            }

            $_SESSION['chat_users'][$from->resourceId] = $message->user_id;
        }

        if (!empty($message->receiverId) && !empty($message->meId) && !empty($message->type) && $message->type == 'allMessages') {
            //verificar se tem permissão de criar esse chat
            $chat = new Chat($this->config);
            $chat = $chat->getOrCreate($message->meId, $message->receiverId);
            // $from->send(json_encode($chat));
        }

        if (!empty($message->receiverId) && !empty($message->meId) && !empty($message->message) && !empty($message->type) && $message->type == 'sendMessage') {
            //enviar para o outro cliente a mensagem
            $chat = new Chat($this->config);
            $messageSend = $chat->sendMessage($message->meId, $message->receiverId, $message->message);
            if (is_array($messageSend)) {
                $connectionsReceiverId = [];

                foreach ($_SESSION['chat_users'] as $key => $value) {
                    if ($value == $message->receiverId) {
                        $connectionsReceiverId[] = $key;
                    }
                }

                $messageSend['type'] = 'new_message';
                $messageSend['created_at'] = date_format(date_create($messageSend['created_at']),"d/m/Y H:i:s");
    
                foreach ( $this->clients as $client ) {
                    if (in_array($client->resourceId, $connectionsReceiverId)) {
                        $client->send(json_encode($messageSend));
                    }
                }
            }

        }

        // foreach ( $this->clients as $client ) {
            
        //     if ( $from->resourceId == $client->resourceId ) {
        //         continue;
        //     }

        //     $client->send( "Client $from->resourceId said $msg" );
        // }
    }

    public function onClose(ConnectionInterface $conn) {
        unset($_SESSION['chat_users'][$conn->resourceId]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }

    function isJson($string) {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
     }
}
