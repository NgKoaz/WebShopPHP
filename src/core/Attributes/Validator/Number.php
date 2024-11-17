<?php

namespace App\core\Attributes\Validator;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Number extends Validator
{
    public function __construct(string $errorMessage = "Must be a number!")
    {
        parent::__construct($errorMessage);
    }

    public function isValid($property): bool
    {
        return is_numeric($property);
    }
}
