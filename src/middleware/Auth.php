<?php

namespace App\Middleware;

use Closure;
use App\core\Attributes\MiddlewareAttribute\Middleware;
use App\core\Request;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Auth extends Middleware
{
    public function __construct(public string $routeUri)
    {
        parent::__construct();
    }

    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasUserLoggedIn()) {
            header("Location: {$this->routeUri}");
            http_response_code(302);
            die();
        }
        $next($request);
    }
}
