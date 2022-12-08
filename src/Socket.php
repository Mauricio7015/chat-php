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
        
        if (!empty($message->user_id) || !empty($message->meId)) {
            if (!isset($_SESSION['chat_users'])) {
                $_SESSION['chat_users'] = [];
            }
            $_SESSION['chat_users'][$from->resourceId] = !empty($message->user_id) ? $message->user_id : $message->meId;
        }

        if (!empty($message->receiverId) && !empty($message->meId) && !empty($message->type) && $message->type == 'allMessages') {
            //verificar se tem permissão de criar esse chat
            $chat = new Chat($this->config);
            $chat->getOrCreate($message->meId, $message->receiverId);
            $messages = $chat->getMessages();
            foreach ($messages as $key => $message) {
                if ($message['anexo']) {
                    $messages[$key]['anexo'] = $this->config->getDownloadAnexos().'/'.$message['anexo'];
                }
            }
            $data = [
                'type' => 'all_messages',
                'messages' => $messages
            ];
            $from->send(json_encode($data));
        }

        if (!empty($message->receiverId) && !empty($message->meId) && !empty($message->type) && $message->type == 'anexo' && !empty($message->name) && !empty($message->file)) {
            $nameFile = $this->generateRandomString(150).'.'.pathinfo($message->name, PATHINFO_EXTENSION);
            $path = $this->config->getPathAnexos().'/'.$nameFile;
            file_put_contents($path, file_get_contents($message->file));

            $chat = new Chat($this->config);
            $messageSend = $chat->sendAnexo($message->meId, $message->receiverId, $nameFile, $message->name);
            if (is_array($messageSend)) {
                $connectionsReceiverId = [];

                foreach ($_SESSION['chat_users'] as $key => $value) {
                    if ($value == $message->receiverId) {
                        $connectionsReceiverId[] = $key;
                    }
                }

                if ($messageSend['anexo']) {
                    $messageSend['anexo'] = $this->config->getDownloadAnexos().'/'.$nameFile;
                }

                $messageSend['type'] = 'new_message';
                $messageSend['created_at'] = date_format(date_create($messageSend['created_at']),"d/m/Y H:i:s");

                foreach ( $this->clients as $client ) {
                    if (in_array($client->resourceId, $connectionsReceiverId)) {
                        $client->send(json_encode($messageSend));
                    }
                }

                $messageSend['type'] = 'me_message';
                $from->send(json_encode($messageSend));
            }
        }

        if (!empty($message->receiverId) && !empty($message->meId) && !empty($message->message) && !empty($message->type) && $message->type == 'sendMessage') {
            $chat = new Chat($this->config);
            $messageSend = $chat->sendMessage($message->meId, $message->receiverId, $message->message);
            if (is_array($messageSend)) {
                $connectionsReceiverId = [];

                foreach ($_SESSION['chat_users'] as $key => $value) {
                    if ($value == $message->receiverId) {
                        $connectionsReceiverId[] = $key;
                    }
                }

                if ($messageSend['anexo']) {
                    $messageSend['anexo'] = $this->config->getDownloadAnexos().'/'.$nameFile;
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

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
