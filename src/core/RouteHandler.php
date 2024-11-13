<?php

namespace App\core;

use call_user_method_array;
use Error;
use Exception;
use Reflection;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\VarExporter\Internal\Reference;

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

    public function run(Request $request, array $params)
    {
        // Create controller from DI container.
        $container = Container::getInstance();
        $instance = $container->create($this->controller);

        // Check if there is a model as param.
        $reflectionAction = new ReflectionMethod($this->controller, $this->action);
        $actionParams = $reflectionAction->getParameters();
        foreach ($actionParams as $actionParam) {
            if (!$actionParam->hasType()) continue;

            $type = $actionParam->getType();
            $typeName = (string) $type;
            if ($type->isBuiltin()) continue;
            $varName = $actionParam->getName();

            $reflectionClass = new ReflectionClass($typeName);
            $nameParentClass = $reflectionClass->getParentClass()->getName();
            if ($nameParentClass != Model::class) {
                throw new Error("[ERROR] Your model must inherit from " . Model::class);
            }

            // Pass model param.
            $model = $reflectionClass->newInstance();
            $model->parse($_POST);
            $params[$varName] = $model;
        }

        $next = function ($request) use ($instance, $params) {
            try {
                if (count($params) > 0)
                    call_user_func_array([$instance, $this->action], $params);
                else {
                    call_user_func([$instance, $this->action]);
                }
            } catch (Error $err) {
                echo $err;
                throw new Error("[Hint] $this->controller@$this->action may too few arguments. The params auto pass in: " . print_r($params, true));
            }
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
