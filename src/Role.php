<?php

namespace ChatPhp\Chat;

use ChatPhp\Chat\Config;

class Role {

    private $config;
    private $conn;

    public function __construct(Config $config)
    {
        $this->config = $config;

        if ($config->getConnectionDB()) {
            $this->conn = $config->getConnectionDB();
        }
    }
    
    public function getRoles() {
        if (!$this->conn)
            return [];

        $query  = "SELECT * from roles";
        $result = mysqli_query($this->conn, $query);
        if (!$result)
            return [];

        $rows   = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $rows;
    }

    public function addRole(string $name, string $description = '') {
        $sql = "INSERT INTO roles (name, description)
            VALUES ('$name', '$description') ON DUPLICATE KEY UPDATE    
            description='$description'";

        if ($this->conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function removeRole(int $id) {
        $sql = "DELETE FROM roles WHERE id=$id";

        if ($this->conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
}