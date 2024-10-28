<?php

namespace App\core;

use Attribute;

#[Attribute]
abstract class CoreAttribute
{
    public const HTTP_METHOD_TYPE = 0;
    public const MIDDLEWARE_TYPE = 1;


    public function __construct(public string $type) {}

    public function getType(): string
    {
        return $this->type;
    }
}
