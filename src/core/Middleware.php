<?php

namespace App\core;

use Attribute;
use Closure;

abstract class Middleware extends CoreAttribute
{
    public function __construct()
    {
        parent::__construct(CoreAttribute::MIDDLEWARE_TYPE);
    }

    abstract public function handle(Request $request, Closure $next);
}
