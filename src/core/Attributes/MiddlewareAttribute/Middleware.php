<?php

namespace App\core\Attributes\MiddlewareAttribute;

use App\core\Attributes\CoreAttribute;
use App\core\Request;
use Closure;

abstract class Middleware extends CoreAttribute
{
    public function __construct()
    {
        parent::__construct(CoreAttribute::MIDDLEWARE_TYPE);
    }

    // abstract public function getParams();

    abstract public function handle(Request $request, Closure $next);
}
