<?php

namespace App\core\Attributes\Validator;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class StringLength extends Validator
{
    public function __construct(private int $min = 0, private int $max = PHP_INT_MAX, string $errorMessage = "")
    {
        if ($errorMessage === "") $errorMessage = "Length must be in [$min, $max].";
        parent::__construct($errorMessage);
    }

    public function isValid($property): bool
    {
        if (is_string($property)) {
            $len = strlen($property);
            if ($len >= $this->min && $len <= $this->max) return true;
        }
        return false;
    }
}
