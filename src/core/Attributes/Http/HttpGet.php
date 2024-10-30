<?php

namespace App\core\Attributes\Http;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class HttpGet extends HttpMethod
{
    public function __construct(string $path = "/")
    {
        parent::__construct("GET", $path);
    }
}
