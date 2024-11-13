<?php

namespace App\core\Attributes\Validator;

use App\core\Attributes\Validator\Validator;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Required extends Validator
{
    public function __construct(string $errorMessage = "Required!")
    {
        parent::__construct($errorMessage);
    }

    public function isValid($property): bool
    {
        return isset($property) && $property !== "";
    }
}
