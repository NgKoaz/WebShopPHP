<?php

namespace App\core\attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class HttpPost extends HttpMethod
{
    public function __construct(string $path = "/")
    {
        parent::__construct("POST", $path);
    }
}