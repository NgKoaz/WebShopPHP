<?php

namespace App\core;

use Closure;

abstract class Middleware
{
    abstract public function handle($request, Closure $next);
}
