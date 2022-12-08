<?php

namespace ChatPhp\Chat;

class Config {
    
    private $host;
    private $username;
    private $password;
    private $database;
    private $userTable;
    private $userTableNameColumn;
    private $pathAnexos;
    private $downloadAnexos;

    public function __construct(string $path = 'config.php')
    {
        $data = require $path;

        if (!empty($data['host']))
            $this->host = $data['host'];

        if (!empty($data['username']))
            $this->username = $data['username'];

        if (!empty($data['password']))
            $this->password = $data['password'];

        if (!empty($data['database']))
            $this->database = $data['database'];

        if (!empty($data['user_table']))
            $this->userTable = $data['user_table'];

        if (!empty($data['user_table_name_column']))
            $this->userTableNameColumn = $data['user_table_name_column'];

        if (!empty($data['path_anexos']))
            $this->pathAnexos = $data['path_anexos'];

        if (!empty($data['download_anexos']))
            $this->downloadAnexos = $data['download_anexos'];
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

    public function getUserTableNameColumn() {
        return $this->userTableNameColumn;
    }

    public function getPathAnexos() {
        return $this->pathAnexos;
    }

    public function getDownloadAnexos() {
        return $this->downloadAnexos;
    }
}