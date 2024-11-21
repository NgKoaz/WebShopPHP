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
        // Add param into Get method.
        foreach ($_GET as $key => $value) {
            $params[$key] = $value;
        }

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

        // Just take the params that action need.
        // var_dump($params);

        $paramInAction = [];
        foreach ($actionParams as $actionParam) {
            $paramInAction[$actionParam->getName()] = "";
        }
        $params = array_intersect_key($params, $paramInAction);

        // var_dump($params);
        // echo "SEPI";
        // var_dump($paramInAction);



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
                $next = function ($request) use ($middleware, $next) {
                    $reflection = new ReflectionClass($this->controller);
                    $attrFromClass = $reflection->getAttributes($middleware);
                    $attrFromMethod = $reflection->getMethod($this->action)->getAttributes($middleware);
                    $middleware = (count($attrFromMethod) !== 0) ? $attrFromMethod[0]->newInstance() : ((count($attrFromClass) !== 0) ? $attrFromClass[0]->newInstance() : null);
                    if ($middleware === null) throw new Exception("Middleware $middleware is not found in controller {$this->controller}");

                    $middleware->handle($request, $next);
                };
            }
        }

        $next($request);
    }
}
