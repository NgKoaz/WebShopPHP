<?php

namespace App\core\Attributes\Http;

use App\core\Attributes\CoreAttribute;

class HttpMethod extends CoreAttribute
{
    public function __construct(protected string $method, protected string $path)
    {
        parent::__construct(CoreAttribute::HTTP_METHOD_TYPE);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getHttpMethod(): string
    {
        return $this->method;
    }
}
