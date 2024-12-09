<?php

namespace App\services;

use App\core\ArrayList;


define("CART_SESSION", "CART");
define("PAYMENT_SESSION", "PAYMENT");


class SessionManager
{
    public static string $FLASH = "FLASH";
    private string $PERSISTENT = "PERSISTENT";
    private string $TEMP_MESSAGE = "TEMP_MESSAGE";
    public static string $GOOGLE_AUTH = "GOOGLE_AUTH";

    public function __construct()
    {
        ini_set('session.gc_maxlifetime', 360000);
        ini_set('session.cookie_lifetime', 360000);

        session_start();

        if (!isset($_SESSION[$this->PERSISTENT])) $_SESSION[$this->PERSISTENT] = new ArrayList;
    }

    public function setTempMessage(string $message): void
    {
        $_SESSION[self::$FLASH][$this->TEMP_MESSAGE] = $message;
    }

    public function setFlash(string $name, mixed $value): void
    {
        if (!isset($_SESSION[self::$FLASH])) $_SESSION[self::$FLASH] = [];
        $_SESSION[self::$FLASH][$name] = $value;
    }

    public function getFlash(string $name): mixed
    {
        if (!isset($_SESSION[self::$FLASH])) return null;
        $result = isset($_SESSION[self::$FLASH][$name]) ? $_SESSION[self::$FLASH][$name] : null;
        unset($_SESSION[self::$FLASH][$name]);
        return $result;
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

    public function remove(): void
    {
        session_unset();
        session_destroy();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
    }
}
