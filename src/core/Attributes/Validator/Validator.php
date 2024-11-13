<?php

namespace App\core\Attributes\Validator;

use App\core\Attributes\CoreAttribute;

abstract class Validator extends CoreAttribute
{
    public function __construct(private string $errorMessage)
    {
        parent::__construct(CoreAttribute::VALIDATOR_TYPE);
    }

    abstract public function isValid($property): bool;

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}
