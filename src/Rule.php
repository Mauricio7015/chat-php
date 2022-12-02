<?php

namespace ChatPhp\Chat;

use ChatPhp\Chat\Config;

class Rule {

    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;

        if ($config->getConnectionDB()) {
            $this->conn = $config->getConnectionDB();
        }
    }

    public function addRule(string $role_first_name, string $role_second_name) {
        $query  = "SELECT * from roles where name='$role_first_name'";
        $result = mysqli_query($this->conn, $query);
        if (!$result)
            return false;
        $rows   = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $roleFirst = $rows[0];

        $query  = "SELECT * from roles where name='$role_second_name'";
        $result = mysqli_query($this->conn, $query);
        if (!$result)
            return false;
        $rows   = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $roleSecond = $rows[0];

        if (!$roleFirst || !$roleSecond)
            return false;
            
        $sql = "INSERT INTO rules_blocked (role_first_id, role_second_id)
            VALUES ('".$roleFirst['id']."', '".$roleSecond['id']."')";

        if ($this->conn->query($sql) === TRUE) {
            return true;
        }

        return false;
    }

    public function getRules() {
        $query  = "SELECT rules_blocked.id, role_first.name as role_first_name, role_second.name as role_second_name from rules_blocked 
            INNER JOIN roles as role_first ON (role_first.id = rules_blocked.role_first_id)
            INNER JOIN roles as role_second ON (role_second.id = rules_blocked.role_second_id)";

        $result = mysqli_query($this->conn, $query);
        if (!$result)
            return false;
        $rows   = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $rows;
    }
}