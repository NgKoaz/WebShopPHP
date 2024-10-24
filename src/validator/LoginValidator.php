<?php

class LoginValidator extends Validator
{
    public function validate(array $data): bool
    {
        // $this->setError("password", "Wrong Password");
        return empty($this->getErrors());
    }
}
