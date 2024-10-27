<?php

namespace App\validator;

use App\core\Validator;

class LoginValidator extends Validator
{
    public function validate(array $data): bool
    {
        // $this->setError("password", "Wrong Password");
        return empty($this->getErrors());
    }
}
