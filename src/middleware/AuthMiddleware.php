<?php

namespace App\Middleware;

use Closure;
use App\core\Attributes\MiddlewareAttribute\Middleware;
use App\core\Request;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class AuthMiddleware extends Middleware
{
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(Request $request, Closure $next)
    {
        $user = $request->getUserRoles();
        var_dump($user);
        $next($request);
    }
}
