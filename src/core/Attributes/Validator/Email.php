<?php

namespace App\core\Attributes\Validator;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Email extends Validator
{
    public function __construct(string $errorMessage = "Must be an email!")
    {
        parent::__construct($errorMessage);
    }

    public function isValid($property): bool
    {
        return filter_Var($property, FILTER_VALIDATE_EMAIL);
    }
}
