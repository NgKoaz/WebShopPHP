<?php

namespace App\core;

use ArrayAccess;
use Countable;

class ArrayList implements ArrayAccess, Countable
{
    private array $data = [];

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->data[$offset]);
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function __sleep(): array
    {
        return ['data'];
    }

    public function __wakeup() {}
}
