<?php

namespace App\Middleware;

use Closure;
use App\core\Attributes\MiddlewareAttribute\Middleware;
use App\core\Request;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class RoleMiddleware extends Middleware
{
    private array $roles;

    public function __construct(private string $redirectedUrl, string ...$roles)
    {
        $this->roles = $roles;
        parent::__construct();
    }

    public function handle(Request $request, Closure $next)
    {
        if (!$request->isUserHasRoles($this->roles)) {
            header("Location: {$this->redirectedUrl}");
            http_response_code(302);
            die();
        }
        $next($request);
    }
}
