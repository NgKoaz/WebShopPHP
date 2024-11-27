<?php

namespace App\core;

use App\core\Types\HTMLString;
use App\core\Types\Image;
use ArrayAccess;
use Exception;
use ReflectionClass;

class Model implements ArrayAccess
{
    private bool $runIsValidOneTime = false;
    private array $errors = [];

    public function parse(array $assocArr): void
    {
        foreach ($assocArr as $key => $value) {
            if (property_exists($this, $key)) {
                switch (true) {
                    case is_a($this->$key, HTMLString::class):
                        $this->$key = new HTMLString($value);
                        break;
                    case is_a($this->$key, Image::class):
                        $this->$key = new Image($value);
                        break;
                    case is_array($value):
                        $this->$key = $value;
                        break;
                    case $this->json_validate($value):
                        $this->$key = $value;
                        break;
                    default:
                        $this->$key = htmlspecialchars($value);
                        break;
                }
            }
        }
    }

    public function isValid(): bool
    {
        if ($this->runIsValidOneTime) return empty($this->errors);
        $this->runIsValidOneTime = true;

        $reflectionClass = new ReflectionClass($this);
        $properties = $reflectionClass->getProperties();
        foreach ($properties as $property) {
            $propValue = $property->getValue($this);
            $attributes = $property->getAttributes();
            foreach ($attributes as $attribute) {
                $attrInstance = $attribute->newInstance();
                if ($attrInstance->isValid($propValue)) continue;

                if ($propValue !== null && $property->getType()->getName() === Image::class) $propValue->removeImage();
                $this->errors[$property->getName()][] = $attrInstance->getErrorMessage();
            }
        }
        return empty($this->errors);
    }

    public function getFullError(): array
    {
        return $this->errors;
    }

    public function getError(string $property): mixed
    {
        return isset($this->errors[$property]) ? $this->errors[$property] : null;
    }

    public function setError(string $name, string $value)
    {
        if (!isset($this->errors[$name]))
            $this->errors[$name] = [];
        $this->errors[$name][] = $value;
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

    public function json_validate($value)
    {
        if (is_string($value)) {
            json_decode($value);
            return (json_last_error() == JSON_ERROR_NONE);
        }
        return false;
    }
}
