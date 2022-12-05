<?php

namespace ChatPhp\Chat\DB;

use ChatPhp\Chat\User;
use ChatPhp\Chat\Config;

class Chat {

    private $config;
    private $conn;
    private $chat;

    public function __construct(Config $config)
    {
        $this->config = $config;

        if ($config->getConnectionDB()) {
            $this->conn = $config->getConnectionDB();
        }
    }

    public function getOrCreate(int $userId, int $otherUserId) {
        $query = "select * from chats where (user_id = $userId and other_user_id=$otherUserId) or (user_id=$otherUserId and other_user_id=$userId)";
        $result = mysqli_query($this->conn, $query);
        if (!$result)
            return false;
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        if (!$rows) {
            $query = "insert into chats (user_id, other_user_id) VALUES ($userId,$otherUserId)";

            if ($this->conn->query($query) === TRUE) {
                $query = "select * from chats where (user_id = $userId and other_user_id=$otherUserId) or (user_id=$otherUserId and other_user_id=$userId)";
                $result = mysqli_query($this->conn, $query);
                if (!$result)
                    return false;
                $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
                $this->chat = $rows[0];
                return $rows[0];
            }
    
            return false;
        }
        $this->chat = $rows[0];
        return $rows[0];
    }

    public function sendMessage(int $userId, int $receiverId, string $message) {
        $chat = $this->getOrCreate($userId, $receiverId);
        $chatId = $chat['id'];
        $date = now();

        $query = "insert into messages (chat_id, sender_id, receiver_id, message, created_at) VALUES ($chatId,$userId,$receiverId,'$message','$date')";

        if ($this->conn->query($query) === TRUE) {
            $user_table             = $this->config->getUserTable();
            $user_table_name_column = $this->config->getUserTableNameColumn();
            $query = "SELECT
                    messages.*,
                    $user_table.$user_table_name_column
                FROM
                    messages 
                INNER JOIN $user_table on ($user_table.id = messages.sender_id)
                WHERE
                    chat_id = $chatId 
                    AND sender_id = $userId 
                    AND message = '$message'
                    AND created_at = '$date'
                ORDER BY
                    messages.id DESC 
                    LIMIT 1";

            $result = mysqli_query($this->conn, $query);
            if (!$result)
                return false;
            $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $rows[0];
        }
        return false;
    }

    public function getMessages() {
        if (!$this->chat) {
            return [];
        }

        $user_table             = $this->config->getUserTable();
        $user_table_name_column = $this->config->getUserTableNameColumn();

        $query = "SELECT 
            messages.message,
            DATE_FORMAT(messages.created_at, '%d/%m/%Y %H:%i') as created_at,
            sender.id as sender_id,
            sender.$user_table_name_column as name,
            messages.receiver_id
            FROM messages
            INNER JOIN $user_table as sender ON (sender.id = messages.sender_id)
            ORDER BY messages.id DESC
            LIMIT 100";

        $result = mysqli_query($this->conn, $query);
        if (!$result)
            return false;

        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $rows;
    }
}