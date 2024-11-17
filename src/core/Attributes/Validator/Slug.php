<?php

namespace App\core\Attributes\Validator;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Slug extends Validator
{
    public function __construct(string $errorMessage = "Slug is in wrong format!")
    {
        parent::__construct($errorMessage);
    }

    public function isValid($property): bool
    {
        return preg_match('@^[a-z0-9]+(?:-[a-z0-9]+)*$@', $property);
    }
}
