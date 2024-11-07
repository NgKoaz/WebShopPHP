<?php

namespace App\Middleware;

use Closure;
use App\core\Attributes\MiddlewareAttribute\Middleware;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class AuthMiddleware extends Middleware
{
    public function handle($request, Closure $next)
    {
        // echo "Auth day!!!";
        $next($request);
    }
}
