<?php

abstract class Validator
{
    protected $errors = [];

    abstract public function validate(array $data): bool;

    protected function setError(string $key, string $message)
    {
        $this->errors[$key] = $message;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
