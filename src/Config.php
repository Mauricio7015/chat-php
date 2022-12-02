<?php

namespace ChatPhp\Chat;

class Config {
    
    private $host;
    private $username;
    private $password;
    private $database;
    private $userTable;

    public function set(array $data, string $userTable = 'users')
    {
        if (!empty($data['host']))
            $this->host = $data['host'];

        if (!empty($data['username']))
            $this->username = $data['username'];

        if (!empty($data['password']))
            $this->password = $data['password'];

        if (!empty($data['database']))
            $this->database = $data['database'];

        $this->userTable = $userTable;
    }

    public function getConnectionDB() {
        if (!$this->host || !$this->username || !$this->password)
            return null;

        $conn = new \mysqli($this->host, $this->username, $this->password, $this->database);

        // Check connection
        if ($conn->connect_error)
            return null;

        return $conn;
    }

    public function getUserTable() {
        return $this->userTable;
    }
}