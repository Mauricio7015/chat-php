<?php

namespace ChatPhp\Chat;

use ChatPhp\Chat\User;
use ChatPhp\Chat\Config;

class Chat {

    private $config;
    private $user;
    private $currentUserId;

    public function __construct(Config $config, User $user, $currentUserId)
    {
        $this->config        = $config;
        $this->user          = $user;
        $this->currentUserId = $currentUserId;
    }

    public function makeChat() {
        $users = $this->user->getUsers();
        $currentUserId = $this->currentUserId;
        include 'Html/chat.php';
    }
}