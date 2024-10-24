<?php

class AuthMiddleware extends Middleware
{
    public function handle($request, Closure $next)
    {
        echo "HELLO123";
        $next($request);
    }
}
