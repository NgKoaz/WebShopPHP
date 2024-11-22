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
        ini_set('session.gc_maxlifetime', 1800);
        ini_set('session.cookie_lifetime', 1800);
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

    private function checkEntry(string $entry): void
    {
        if (!isset($_SESSION[$entry])) $_SESSION[$entry] = [];
    }

    public function setInEntry(string $entry, string $name, mixed $value)
    {
        $this->checkEntry($entry);
        $_SESSION[$entry][$name] = $value;
    }

    public function setEntry(string $entry, mixed $value)
    {
        $_SESSION[$entry] = $value;
    }

    public function getEntry(string $entry)
    {
        $this->checkEntry($entry);
        return $_SESSION[$entry];
    }

    public function unsetEntry(string $entry)
    {
        unset($_SESSION[$entry]);
    }

    public function unsetInEntry(string $entry, string $name)
    {
        $this->checkEntry($entry);
        unset($_SESSION[$entry][$name]);
    }

    public function setPersistentEntry(string $name, mixed $value): void
    {
        $_SESSION[$this->PERSISTENT][$name] = $value;
    }

    public function appendPersistentEntry(string $entry, string $name, mixed $value): void
    {
        if (!isset($_SESSION[$this->PERSISTENT][$entry])) {
            $_SESSION[$this->PERSISTENT][$entry] = new ArrayList;
        }

        $_SESSION[$this->PERSISTENT][$entry]->append($name, $value);
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
