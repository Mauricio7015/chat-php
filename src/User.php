<?php

namespace ChatPhp\Chat;

class User {

    private $users = [];

    public function addUser($id, string $name)
    {
        if (empty($this->users[$id])) {
            $this->users[$id] = [
                'id'   => $id,
                'name' => $name
            ];
        }

        return true;
    }

    public function removeUser($id) {
        unset($this->users[$id]);
        return true;
    }

    public function getUsers() {
        return $this->users;
    }
}