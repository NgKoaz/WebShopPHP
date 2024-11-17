<?php

namespace App\core\Attributes\Validator;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Phone extends Validator
{

    public function __construct(string $errorMessage = "Phone is number type!")
    {
        parent::__construct($errorMessage);
    }

    public function isValid($property): bool
    {
        return is_numeric($property);
    }
}
