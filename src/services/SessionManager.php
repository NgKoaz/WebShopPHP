<?php

namespace App\services;

use App\core\ArrayList;

class SessionManager
{
    private string $FLASH = "FLASH";
    private string $PERSISTENT = "PERSISTENT";
    private string $TEMP_MESSAGE = "TEMP_MESSAGE";

    public function __construct()
    {
        session_start();
        $_SESSION[$this->FLASH] = new ArrayList;
        if (!isset($_SESSION[$this->PERSISTENT])) $_SESSION[$this->PERSISTENT] = new ArrayList;
    }

    public function setTempMessage(string $message): void
    {
        $_SESSION[$this->FLASH][$this->TEMP_MESSAGE] = $message;
    }

    public function getTempMessage(): ?string
    {
        return $_SESSION[$this->FLASH][$this->TEMP_MESSAGE];
    }

    public function setPersistentEntry(string $name, mixed $value): void
    {
        if (!isset($_SESSION[$this->PERSISTENT])) $_SESSION[$this->PERSISTENT] = new ArrayList;
        $_SESSION[$this->PERSISTENT][$name] = $value;
    }

    public function getPersistentEntry(string $name): mixed
    {
        return $_SESSION[$this->PERSISTENT][$name];
    }

    public function getPersistent(): ArrayList
    {
        return isset($_SESSION[$this->PERSISTENT]) ? $_SESSION[$this->PERSISTENT] : new ArrayList;
    }

    public function removePersistentEntry(string $name): void
    {
        if (isset($_SESSION[$this->PERSISTENT][$name])) {
            unset($_SESSION[$this->PERSISTENT][$name]);
        }
    }

    public function removePersistent(): void
    {
        $_SESSION[$this->PERSISTENT] = new ArrayList;
    }
}
