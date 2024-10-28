<?php

namespace App\core;

class RouteHandler
{
    /**
     * @var string[]
     */
    public array $middlewares;

    public function __construct(
        public string $controller,
        public string $action = "index",
        $middlewares
    ) {
        $this->middlewares = $middlewares ?? [];
    }

    public function run(Request $request)
    {
        $instance = new $this->controller;
        $next = function ($request) use ($instance) {
            $instance->{$this->action}();
        };

        if (is_array($this->middlewares)) {
            while ($middleware = array_pop($this->middlewares)) {
                $next = function ($request) use ($middleware, $next) {
                    $middleware = new $middleware;
                    $middleware->handle($request, $next);
                };
            }
        }

        $next($request);
    }
}
