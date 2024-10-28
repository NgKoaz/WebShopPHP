<?php

namespace App\middleware;

use Closure;
use App\core\Middleware;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class AuthMiddleware extends Middleware
{
    public function handle($request, Closure $next)
    {
        echo "Auth day!!!";
        $next($request);
    }
}
