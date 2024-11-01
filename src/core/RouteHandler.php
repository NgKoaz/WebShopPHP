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
        array $middlewares = null
    ) {
        $this->middlewares = $middlewares ?? [];
    }

    public function run(Request $request)
    {
        $container = Container::getInstance();
        $instance = $container->create($this->controller);
        $next = function ($request) use ($instance) {
            $instance->{$this->action}();
        };

        if (is_array($this->middlewares)) {
            while ($middleware = array_pop($this->middlewares)) {
                $next = function ($request) use ($middleware, $next, $container) {
                    $middleware = $container->create($middleware);
                    $middleware->handle($request, $next);
                };
            }
        }

        $next($request);
    }
}
