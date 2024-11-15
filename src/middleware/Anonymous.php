<?php

namespace App\Middleware;

use App\core\Attributes\MiddlewareAttribute\Middleware;
use App\core\Request;
use Attribute;
use Closure;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Anonymous extends Middleware
{
    public function __construct(public string $redirectUri = "/")
    {
        parent::__construct();
    }

    public function handle(Request $request, Closure $next)
    {
        if ($request->hasUserLoggedIn()) {
            header("Location: {$this->redirectUri}");
            http_response_code(302);
            die();
        }

        $next($request);
    }
}
