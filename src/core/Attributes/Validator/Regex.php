<?php

namespace App\core\Attributes\Validator;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Regex extends Validator
{
    public function __construct(private string $pattern, string $errorMessage = "Wrong format!")
    {
        parent::__construct($errorMessage);
    }

    public function isValid($property): bool
    {
        return preg_match($this->pattern, $property);
    }
}
