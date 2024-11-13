<?php

namespace App\core;

use ArrayAccess;
use Exception;
use ReflectionClass;

class Model implements ArrayAccess
{
    public array $errors = [];

    public function parse(array $assocArr): void
    {
        foreach ($assocArr as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = htmlspecialchars($value);
            }
        }
    }

    public function isValid(): bool
    {
        $reflectionClass = new ReflectionClass($this);
        $properties = $reflectionClass->getProperties();
        foreach ($properties as $property) {
            var_dump($property->getAttributes());
        }
        return false;
    }





    public function offsetGet(mixed $offset): mixed
    {
        return isset($this->$offset) ? $this->$offset : null;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->$offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($this->offsetExists($offset)) $this->$offset = $value;
        else throw new Exception("Not exists!");
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new Exception("Not implement!");
    }
}
